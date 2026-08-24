<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PRIX LIVRAISON PAR WILAYA
    |--------------------------------------------------------------------------
    */

    const DELIVERY_PRICES = [

        'Adrar' => [
            'stop_desk' => 1000,
            'a_domicile' => 1200,
        ],

        'Chlef' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        'Laghouat' => [
            'stop_desk' => 800,
            'a_domicile' => 1000,
        ],

        'Oum El Bouaghi' => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'Batna' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        'Béjaïa' => [
            'stop_desk' => 600,
            'a_domicile' => 800,
        ],

        'Biskra' => [
            'stop_desk' => 800,
            'a_domicile' => 1000,
        ],

        'Béchar' => [
            'stop_desk' => 1000,
            'a_domicile' => 1200,
        ],

        'Blida' => [
            'stop_desk' => 500,
            'a_domicile' => 700,
        ],

        'Bouira' => [
            'stop_desk' => 500,
            'a_domicile' => 700,
        ],

        'Tamanrasset' => [
            'stop_desk' => 1400,
            'a_domicile' => 1600,
        ],

        'Tébessa' => [
            'stop_desk' => 850,
            'a_domicile' => 1050,
        ],

        'Tlemcen' => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'Tiaret' => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'Tizi Ouzou' => [
            'stop_desk' => 550,
            'a_domicile' => 750,
        ],

        'Alger' => [
            'stop_desk' => 300,
            'a_domicile' => 500,
        ],

        'Djelfa' => [
            'stop_desk' => 850,
            'a_domicile' => 1050,
        ],

        'Jijel' => [
            'stop_desk' => 650,
            'a_domicile' => 850,
        ],

        'Sétif' => [
            'stop_desk' => 650,
            'a_domicile' => 850,
        ],

        'Saïda' => [
            'stop_desk' => 800,
            'a_domicile' => 1000,
        ],

        'Skikda' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        'Sidi Bel Abbès' => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'Annaba' => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'Guelma' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        'Constantine' => [
            'stop_desk' => 350,
            'a_domicile' => 600,
        ],

        'Médéa' => [
            'stop_desk' => 600,
            'a_domicile' => 800,
        ],

        'Mostaganem' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        "M'Sila" => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'Mascara' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        'Ouargla' => [
            'stop_desk' => 900,
            'a_domicile' => 1100,
        ],

        'Oran' => [
            'stop_desk' => 350,
            'a_domicile' => 600,
        ],

        'El Bayadh' => [
            'stop_desk' => 900,
            'a_domicile' => 1100,
        ],

        'Illizi' => [
            'stop_desk' => 1300,
            'a_domicile' => 1500,
        ],

        'Bordj Bou Arréridj' => [
            'stop_desk' => 600,
            'a_domicile' => 800,
        ],

        'Boumerdès' => [
            'stop_desk' => 450,
            'a_domicile' => 650,
        ],

        'El Tarf' => [
            'stop_desk' => 800,
            'a_domicile' => 1000,
        ],

        'Tindouf' => [
            'stop_desk' => 1200,
            'a_domicile' => 1400,
        ],

        'Tissemsilt' => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'El Oued' => [
            'stop_desk' => 900,
            'a_domicile' => 1100,
        ],

        'Khenchela' => [
            'stop_desk' => 800,
            'a_domicile' => 1000,
        ],

        'Souk Ahras' => [
            'stop_desk' => 800,
            'a_domicile' => 1000,
        ],

        'Tipaza' => [
            'stop_desk' => 500,
            'a_domicile' => 700,
        ],

        'Mila' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        'Aïn Defla' => [
            'stop_desk' => 600,
            'a_domicile' => 800,
        ],

        'Naâma' => [
            'stop_desk' => 950,
            'a_domicile' => 1150,
        ],

        'Aïn Témouchent' => [
            'stop_desk' => 750,
            'a_domicile' => 950,
        ],

        'Ghardaïa' => [
            'stop_desk' => 850,
            'a_domicile' => 1050,
        ],

        'Relizane' => [
            'stop_desk' => 700,
            'a_domicile' => 900,
        ],

        'Timimoun' => [
            'stop_desk' => 1100,
            'a_domicile' => 1300,
        ],

        'Bordj Badji Mokhtar' => [
            'stop_desk' => 1500,
            'a_domicile' => 1700,
        ],

        'Ouled Djellal' => [
            'stop_desk' => 900,
            'a_domicile' => 1100,
        ],

        'Béni Abbès' => [
            'stop_desk' => 1100,
            'a_domicile' => 1300,
        ],

        'In Salah' => [
            'stop_desk' => 1300,
            'a_domicile' => 1500,
        ],

        'In Guezzam' => [
            'stop_desk' => 1500,
            'a_domicile' => 1700,
        ],

        'Touggourt' => [
            'stop_desk' => 900,
            'a_domicile' => 1100,
        ],

        'Djanet' => [
            'stop_desk' => 1400,
            'a_domicile' => 1600,
        ],

        "El M'Ghair" => [
            'stop_desk' => 950,
            'a_domicile' => 1150,
        ],

        'El Meniaa' => [
            'stop_desk' => 950,
            'a_domicile' => 1150,
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | 58 WILAYAS
    |--------------------------------------------------------------------------
    */

    const WILAYAS = [
        '01' => 'Adrar',
        '02' => 'Chlef',
        '03' => 'Laghouat',
        '04' => 'Oum El Bouaghi',
        '05' => 'Batna',
        '06' => 'Béjaïa',
        '07' => 'Biskra',
        '08' => 'Béchar',
        '09' => 'Blida',
        '10' => 'Bouira',
        '11' => 'Tamanrasset',
        '12' => 'Tébessa',
        '13' => 'Tlemcen',
        '14' => 'Tiaret',
        '15' => 'Tizi Ouzou',
        '16' => 'Alger',
        '17' => 'Djelfa',
        '18' => 'Jijel',
        '19' => 'Sétif',
        '20' => 'Saïda',
        '21' => 'Skikda',
        '22' => 'Sidi Bel Abbès',
        '23' => 'Annaba',
        '24' => 'Guelma',
        '25' => 'Constantine',
        '26' => 'Médéa',
        '27' => 'Mostaganem',
        '28' => "M'Sila",
        '29' => 'Mascara',
        '30' => 'Ouargla',
        '31' => 'Oran',
        '32' => 'El Bayadh',
        '33' => 'Illizi',
        '34' => 'Bordj Bou Arréridj',
        '35' => 'Boumerdès',
        '36' => 'El Tarf',
        '37' => 'Tindouf',
        '38' => 'Tissemsilt',
        '39' => 'El Oued',
        '40' => 'Khenchela',
        '41' => 'Souk Ahras',
        '42' => 'Tipaza',
        '43' => 'Mila',
        '44' => 'Aïn Defla',
        '45' => 'Naâma',
        '46' => 'Aïn Témouchent',
        '47' => 'Ghardaïa',
        '48' => 'Relizane',
        '49' => 'Timimoun',
        '50' => 'Bordj Badji Mokhtar',
        '51' => 'Ouled Djellal',
        '52' => 'Béni Abbès',
        '53' => 'In Salah',
        '54' => 'In Guezzam',
        '55' => 'Touggourt',
        '56' => 'Djanet',
        '57' => "El M'Ghair",
        '58' => 'El Meniaa',
    ];

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT PAGE
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $cart = $this->currentCart();

        $cart->load('items.variant.product');

        $wilayas = self::WILAYAS;

        $deliveryPrices = self::DELIVERY_PRICES;

        return view(
            'shop.checkout',
            compact(
                'cart',
                'wilayas',
                'deliveryPrices'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE ORDER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $rules = [

            'shipping_name' => [
                'required',
                'string',
                'max:255',
            ],

            'shipping_phone' => [
                'required',
                'string',
                'max:20',
            ],

            'shipping_address' => [
                'required',
                'string',
            ],

            'shipping_wilaya' => [
                'required',
                'string',
                'in:' . implode(',', array_values(self::WILAYAS)),
            ],

            'shipping_commune' => [
                'required',
                'string',
            ],

            'delivery_type' => [
                'required',
                'in:stop_desk,a_domicile',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | EMAIL CLIENT GUEST
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {

            $rules['guest_email'] = [
                'required',
                'email',
            ];
        }

        $validated = $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | PANIER ACTUEL
        |--------------------------------------------------------------------------
        */

        $cart = $this->currentCart();

        $cart->load('items.variant.product');


        /*
        |--------------------------------------------------------------------------
        | PANIER VIDE
        |--------------------------------------------------------------------------
        */

        if ($cart->items->isEmpty()) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Votre panier est vide.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | WILAYA + TYPE LIVRAISON
        |--------------------------------------------------------------------------
        */

        $wilaya = $validated['shipping_wilaya'];

        $deliveryType = $validated['delivery_type'];


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LE PRIX DE LIVRAISON
        |--------------------------------------------------------------------------
        */

        if (!isset(self::DELIVERY_PRICES[$wilaya])) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Le tarif de livraison pour cette wilaya n\'est pas configuré.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LE TYPE DE LIVRAISON
        |--------------------------------------------------------------------------
        */

        if (!isset(
            self::DELIVERY_PRICES[$wilaya][$deliveryType]
        )) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Le tarif de livraison sélectionné n\'est pas disponible.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PRIX FINAL LIVRAISON
        |--------------------------------------------------------------------------
        */

        $shippingCost =
            self::DELIVERY_PRICES[$wilaya][$deliveryType];


        /*
        |--------------------------------------------------------------------------
        | CRÉER LA COMMANDE
        |--------------------------------------------------------------------------
        */

        try {

            $order = DB::transaction(function () use (
                $validated,
                $cart,
                $shippingCost
            ) {

                /*
                |--------------------------------------------------------------------------
                | VÉRIFICATION DES PRODUITS
                |--------------------------------------------------------------------------
                */

                foreach ($cart->items as $item) {

                    $variant = $item->variant;

                    if (!$variant) {

                        throw new \RuntimeException(
                            'Une variante du panier est introuvable.'
                        );
                    }

                    if (!$variant->product) {

                        throw new \RuntimeException(
                            "Le produit de la variante '{$variant->label}' est introuvable."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VÉRIFICATION DU PRIX
                    |--------------------------------------------------------------------------
                    */

                    if ($variant->final_price === null) {

                        throw new \RuntimeException(
                            "Le prix de la variante '{$variant->label}' est manquant."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VÉRIFICATION STOCK
                    |--------------------------------------------------------------------------
                    */

                    if ($variant->stock < $item->quantity) {

                        throw new \RuntimeException(
                            "Stock insuffisant pour '{$variant->product->name}'. "
                            . "Stock disponible: {$variant->stock}, "
                            . "quantité demandée: {$item->quantity}."
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | SOUS-TOTAL
                |--------------------------------------------------------------------------
                */

                $subtotal = $cart->items->sum(
                    function ($item) {

                        return
                            $item->quantity *
                            (float) $item->variant->final_price;
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | TOTAL
                |--------------------------------------------------------------------------
                */

                $total = $subtotal + $shippingCost;


                /*
                |--------------------------------------------------------------------------
                | CRÉATION COMMANDE
                |--------------------------------------------------------------------------
                |
                | IMPORTANT :
                |
                | Le nouveau statut est explicitement "pending".
                |
                */
                $order = Order::create([
                    'user_id' => Auth::id(),
                
                    'shipping_name' => $validated['shipping_name'],
                    'shipping_phone' => $validated['shipping_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'shipping_wilaya' => $validated['shipping_wilaya'],
                    'shipping_city' => $validated['shipping_wilaya'],
                    'shipping_commune' => $validated['shipping_commune'],
                    'shipping_country' => 'Algérie',
                
                    'delivery_type' => $validated['delivery_type'],
                    'notes' => $validated['notes'] ?? null,
                
                    'guest_email' => $validated['guest_email'] ?? null,
                    'is_guest' => !Auth::check(),
                
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'currency' => 'DZD',
                
                    // مهم جداً
                    'status' => 'pending',
                ]);

                /*
                |--------------------------------------------------------------------------
                | ORDER ITEMS
                |--------------------------------------------------------------------------
                */

                foreach ($cart->items as $item) {

                    $variant = $item->variant;

                    $unitPrice =
                        (float) $variant->final_price;

                    $itemTotal =
                        $item->quantity * $unitPrice;

                    $order->items()->create([

                        'product_variant_id' =>
                            $variant->id,

                        'product_name' =>
                            $variant->product->name,

                        'variant_label' =>
                            $variant->label,

                        'unit_price' =>
                            $unitPrice,

                        'quantity' =>
                            $item->quantity,

                        'total' =>
                            $itemTotal,
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | VIDER LE PANIER
                |--------------------------------------------------------------------------
                */

                $cart->items()->delete();


                return $order;
            });

        } catch (\RuntimeException $e) {

            Log::error(
                'Checkout error: ' .
                $e->getMessage()
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION ADMIN
        |--------------------------------------------------------------------------
        */

        try {

            $admins = \App\Models\User::where(
                'is_admin',
                true
            )->get();

            foreach ($admins as $admin) {

                $admin->notify(
                    new \App\Notifications\NewOrderNotification(
                        $order
                    )
                );
            }

        } catch (\Exception $e) {

            Log::error(
                'Admin notification error: ' .
                $e->getMessage()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | EMAIL CLIENT
        |--------------------------------------------------------------------------
        */

        $clientEmail = Auth::check()
            ? Auth::user()->email
            : ($validated['guest_email'] ?? null);

        if ($clientEmail) {

            try {

                $order->load('items');

                Mail::to($clientEmail)->send(
                    new \App\Mail\OrderConfirmationMail(
                        $order
                    )
                );

            } catch (\Exception $e) {

                Log::error(
                    'Email checkout: ' .
                    $e->getMessage()
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT VERS PAIEMENT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'payment.show',
                $order
            )
            ->with(
                'success',
                "Commande {$order->order_number} créée ✅"
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CURRENT CART
    |--------------------------------------------------------------------------
    */

    private function currentCart(): Cart
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR CONNECTÉ
        |--------------------------------------------------------------------------
        */

        if (Auth::check()) {

            return Cart::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VISITEUR GUEST
        |--------------------------------------------------------------------------
        */

        return Cart::firstOrCreate([

            'session_id' =>
                session()->getId(),

            'user_id' =>
                null,
        ]);
    }
}