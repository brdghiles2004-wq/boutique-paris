<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Services\DeliveryService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Liste des commandes
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Order::with([
            'user',
            'items',
        ])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'paid'       => Order::where('status', 'paid')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
            'refunded'   => Order::where('status', 'refunded')->count(),
        ];

        return view(
            'admin.orders.index',
            compact(
                'orders',
                'status',
                'counts'
            )
        );
    }


    /**
     * Afficher une commande
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.variant.product',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }


    /**
     * Modifier le statut d'une commande
     *
     * Gestion du stock:
     *
     * pending/processing -> paid
     *     => retrait du stock
     *
     * paid -> shipped -> delivered
     *     => aucun nouveau retrait
     *
     * paid/shipped/delivered -> cancelled/refunded
     *     => restauration du stock
     *
     * Gestion email:
     *
     * Chaque changement réel de statut
     * envoie un email au client.
     */
    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'status' => [
                'required',
                'in:pending,processing,paid,shipped,delivered,cancelled,refunded',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ANCIEN / NOUVEAU STATUT
        |--------------------------------------------------------------------------
        */

        $newStatus = $request->status;

        $oldStatus = $order->status;


        /*
        |--------------------------------------------------------------------------
        | SI AUCUN CHANGEMENT
        |--------------------------------------------------------------------------
        */

        if ($oldStatus === $newStatus) {

            return back()->with(
                'success',
                'Le statut est déjà "' . $newStatus . '".'
            );
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $order,
                $oldStatus,
                $newStatus
            ) {

                /*
                |--------------------------------------------------------------------------
                | 1. RETIRER LE STOCK
                |--------------------------------------------------------------------------
                |
                | On retire le stock uniquement quand la commande
                | passe réellement à "paid".
                |
                */

                if (
                    $newStatus === 'paid'
                    &&
                    !in_array(
                        $oldStatus,
                        [
                            'paid',
                            'shipped',
                            'delivered',
                        ],
                        true
                    )
                ) {

                    $order->load([
                        'items.variant.product',
                    ]);


                    foreach ($order->items as $item) {

                        $variant = $item->variant;


                        /*
                        | Variante introuvable
                        */

                        if (!$variant) {

                            throw new \RuntimeException(
                                "La variante du produit est introuvable "
                                . "pour la commande {$order->order_number}."
                            );
                        }


                        $quantity =
                            (int) $item->quantity;


                        /*
                        | Quantité invalide
                        */

                        if ($quantity <= 0) {
                            continue;
                        }


                        /*
                        | Vérification du stock
                        */

                        if ($variant->stock < $quantity) {

                            throw new \RuntimeException(
                                "Stock insuffisant pour {$item->product_name}. "
                                . "Stock disponible: {$variant->stock}, "
                                . "quantité demandée: {$quantity}."
                            );
                        }


                        /*
                        | Déduction du stock
                        */

                        $variant->decrement(
                            'stock',
                            $quantity
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | 2. RESTAURER LE STOCK
                |--------------------------------------------------------------------------
                |
                | Si une commande qui avait déjà retiré le stock
                | devient cancelled/refunded.
                |
                */

                if (
                    in_array(
                        $newStatus,
                        [
                            'cancelled',
                            'refunded',
                        ],
                        true
                    )
                    &&
                    in_array(
                        $oldStatus,
                        [
                            'paid',
                            'shipped',
                            'delivered',
                        ],
                        true
                    )
                ) {

                    $order->load([
                        'items.variant',
                    ]);


                    foreach ($order->items as $item) {

                        $variant = $item->variant;


                        if (!$variant) {
                            continue;
                        }


                        $quantity =
                            (int) $item->quantity;


                        if ($quantity > 0) {

                            $variant->increment(
                                'stock',
                                $quantity
                            );
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | 3. MISE À JOUR DU STATUT
                |--------------------------------------------------------------------------
                */

                $data = [
                    'status' => $newStatus,
                ];


                /*
                | Date d'expédition
                */

                if (
                    $newStatus === 'shipped'
                    &&
                    !$order->shipped_at
                ) {

                    $data['shipped_at'] = now();
                }


                /*
                | Mise à jour
                */

                $order->update($data);
            });


            /*
            |--------------------------------------------------------------------------
            | 4. ENVOYER EMAIL AU CLIENT
            |--------------------------------------------------------------------------
            |
            | On est volontairement après la transaction.
            |
            */

            $this->sendStatusEmail(
                $order->fresh()
            );


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            return back()->with(
                'success',
                'Statut de la commande mis à jour ✅'
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | LOG ERREUR
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Order status update error',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'old_status' =>
                        $oldStatus,

                    'new_status' =>
                        $newStatus,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | ERROR
            |--------------------------------------------------------------------------
            */

            return back()->with(
                'error',
                'Impossible de modifier la commande: '
                . $e->getMessage()
            );
        }
    }


    /**
     * Envoyer l'email de changement de statut au client
     */
    private function sendStatusEmail(
        Order $order
    ): void {

        try {

            /*
            |--------------------------------------------------------------------------
            | Charger le user
            |--------------------------------------------------------------------------
            */

            $order->loadMissing('user');


            /*
            |--------------------------------------------------------------------------
            | Trouver l'email client
            |--------------------------------------------------------------------------
            */

            $clientEmail = null;


            /*
            | Client connecté
            */

            if (
                $order->user
                &&
                !empty($order->user->email)
            ) {

                $clientEmail =
                    $order->user->email;
            }


            /*
            | Client guest
            */

            elseif (
                !empty($order->guest_email)
            ) {

                $clientEmail =
                    $order->guest_email;
            }


            /*
            |--------------------------------------------------------------------------
            | Email introuvable
            |--------------------------------------------------------------------------
            */

            if (!$clientEmail) {

                Log::warning(
                    'Order status email skipped: client email missing.',
                    [
                        'order_id' =>
                            $order->id,

                        'order_number' =>
                            $order->order_number,

                        'status' =>
                            $order->status,
                    ]
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | LABEL DU STATUT
            |--------------------------------------------------------------------------
            */

            $statusLabel = match ($order->status) {

                'pending' =>
                    'En attente',

                'processing' =>
                    'En préparation',

                'paid' =>
                    'Payée',

                'shipped' =>
                    'Expédiée',

                'delivered' =>
                    'Livrée',

                'cancelled' =>
                    'Annulée',

                'refunded' =>
                    'Remboursée',

                default =>
                    ucfirst($order->status),
            };


            /*
            |--------------------------------------------------------------------------
            | MESSAGE DU STATUT
            |--------------------------------------------------------------------------
            */

            $statusMessage = match ($order->status) {

                'pending' =>
                    'Votre commande est actuellement en attente.',

                'processing' =>
                    'Votre commande est maintenant en cours de préparation.',

                'paid' =>
                    'Votre paiement a été confirmé. Votre commande est maintenant en préparation.',

                'shipped' =>
                    'Votre commande a été expédiée et est maintenant en route.',

                'delivered' =>
                    'Votre commande a été livrée avec succès. Merci pour votre confiance.',

                'cancelled' =>
                    'Votre commande a été annulée.',

                'refunded' =>
                    'Votre commande a été remboursée.',

                default =>
                    'Le statut de votre commande a été mis à jour.',
            };


            /*
            |--------------------------------------------------------------------------
            | ENVOYER L'EMAIL
            |--------------------------------------------------------------------------
            */

            Mail::to($clientEmail)->send(
                new OrderStatusMail(
                    $order,
                    $statusLabel,
                    $statusMessage
                )
            );


            /*
            |--------------------------------------------------------------------------
            | LOG SUCCESS
            |--------------------------------------------------------------------------
            */

            Log::info(
                'Order status email sent successfully.',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'email' =>
                        $clientEmail,

                    'status' =>
                        $order->status,
                ]
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Si l'envoi email échoue, la commande reste quand même
            | correctement mise à jour.
            |
            */

            Log::error(
                'Order status email error.',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'status' =>
                        $order->status,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }
    }


    /**
     * Créer plusieurs expéditions en même temps
     */
    public function bulkShip(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'order_ids' =>
                'required|array|min:1',

            'order_ids.*' =>
                'integer|exists:orders,id',

            'company' =>
                'required|in:yalidine,zr,maystro,ecotrack',

            'weight' =>
                'nullable|numeric|min:0.1',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DELIVERY SERVICE
        |--------------------------------------------------------------------------
        */

        $delivery =
            new DeliveryService();


        $success = 0;

        $errors = [];


        /*
        |--------------------------------------------------------------------------
        | COMMANDES
        |--------------------------------------------------------------------------
        */

        $orders = Order::whereIn(
            'id',
            $request->order_ids
        )
        ->with([
            'items',
            'user',
        ])
        ->get();


        /*
        |--------------------------------------------------------------------------
        | TRAITEMENT
        |--------------------------------------------------------------------------
        */

        foreach ($orders as $order) {

            try {

                /*
                | Ancien statut
                */

                $oldStatus =
                    $order->status;


                /*
                |--------------------------------------------------------------------------
                | CRÉER EXPÉDITION
                |--------------------------------------------------------------------------
                */

                $result =
                    $delivery->createShipment(
                        $order,
                        $request->company,
                        $request->weight ?? 0.5
                    );


                /*
                |--------------------------------------------------------------------------
                | METTRE À JOUR
                |--------------------------------------------------------------------------
                */

                $order->update([
                    'tracking_number' =>
                        $result['tracking'],

                    'delivery_company' =>
                        $result['company'],

                    'delivery_status' =>
                        'shipped',

                    'status' =>
                        'shipped',

                    'shipped_at' =>
                        now(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | EMAIL CLIENT
                |--------------------------------------------------------------------------
                |
                | Seulement si le statut était différent.
                |
                */

                if ($oldStatus !== 'shipped') {

                    $this->sendStatusEmail(
                        $order->fresh()
                    );
                }


                $success++;

            } catch (\Throwable $e) {

                /*
                | Erreur
                */

                $errors[] =
                    "Commande {$order->order_number}: "
                    . $e->getMessage();


                Log::error(
                    'Bulk delivery error',
                    [
                        'order_id' =>
                            $order->id,

                        'order_number' =>
                            $order->order_number,

                        'company' =>
                            $request->company,

                        'error' =>
                            $e->getMessage(),
                    ]
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MESSAGE
        |--------------------------------------------------------------------------
        */

        $message =
            "{$success} envoi(s) créé(s) ✅";


        if (!empty($errors)) {

            $message .=
                ' — Erreurs: '
                .
                implode(
                    ', ',
                    $errors
                );
        }


        return back()->with(
            'success',
            $message
        );
    }


    /**
     * Créer une expédition pour une seule commande
     */
    public function shipSingle(
        Request $request,
        Order $order
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'company' =>
                'required|in:yalidine,zr,maystro,ecotrack',

            'weight' =>
                'nullable|numeric|min:0.1',
        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | ANCIEN STATUT
            |--------------------------------------------------------------------------
            */

            $oldStatus =
                $order->status;


            /*
            |--------------------------------------------------------------------------
            | DELIVERY SERVICE
            |--------------------------------------------------------------------------
            */

            $delivery =
                new DeliveryService();


            /*
            |--------------------------------------------------------------------------
            | CRÉER EXPÉDITION
            |--------------------------------------------------------------------------
            */

            $result =
                $delivery->createShipment(
                    $order,
                    $request->company,
                    $request->weight ?? 0.5
                );


            /*
            |--------------------------------------------------------------------------
            | METTRE À JOUR
            |--------------------------------------------------------------------------
            */

            $order->update([
                'tracking_number' =>
                    $result['tracking'],

                'delivery_company' =>
                    $result['company'],

                'delivery_status' =>
                    'shipped',

                'status' =>
                    'shipped',

                'shipped_at' =>
                    now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | EMAIL CLIENT
            |--------------------------------------------------------------------------
            */

            if ($oldStatus !== 'shipped') {

                $this->sendStatusEmail(
                    $order->fresh()
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            return back()->with(
                'success',
                "Envoi créé ✅ — Tracking: {$result['tracking']}"
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | LOG
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Single delivery error',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'company' =>
                        $request->company,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | ERROR
            |--------------------------------------------------------------------------
            */

            return back()->with(
                'error',
                'Erreur: ' .
                $e->getMessage()
            );
        }
    }
}