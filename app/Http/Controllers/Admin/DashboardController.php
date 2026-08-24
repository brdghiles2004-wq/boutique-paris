<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | 1. STATISTIQUES PRINCIPALES
        |--------------------------------------------------------------------------
        */

        $totalOrders = Order::count();

        $pendingOrders = Order::where('status', 'pending')->count();

        $paidOrders = Order::where('status', 'paid')->count();

        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $totalClients = User::where('is_admin', false)->count();

        $newClientsToday = User::where('is_admin', false)
            ->whereDate('created_at', today())
            ->count();

        $totalProducts = Product::count();

        $outOfStock = Product::where('is_active', true)
            ->whereDoesntHave('variants', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 2. COMMANDES PAYÉES
        |--------------------------------------------------------------------------
        */

        $paidOrdersQuery = Order::where('status', 'paid');

        $paidOrderIds = (clone $paidOrdersQuery)->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | 3. CHIFFRE D'AFFAIRES
        |--------------------------------------------------------------------------
        */

        $totalRevenue = (float) (
            (clone $paidOrdersQuery)->sum('total')
        );


        /*
        |--------------------------------------------------------------------------
        | 4. COÛT DES PRODUITS VENDUS
        |--------------------------------------------------------------------------
        */

        $soldItems = collect();

        if ($paidOrderIds->isNotEmpty()) {
            $soldItems = OrderItem::whereIn('order_id', $paidOrderIds)
                ->with([
                    'variant.product',
                ])
                ->get();
        }

        $totalCostSold = 0;

        foreach ($soldItems as $item) {

            $costPrice = (float) (
                $item->variant?->product?->cost_price ?? 0
            );

            $quantity = (int) (
                $item->quantity ?? 0
            );

            $totalCostSold += $costPrice * $quantity;
        }


        /*
        |--------------------------------------------------------------------------
        | 5. BÉNÉFICE NET RÉEL
        |--------------------------------------------------------------------------
        */

        $netProfit = $totalRevenue - $totalCostSold;


        /*
        |--------------------------------------------------------------------------
        | 6. MARGE NETTE
        |--------------------------------------------------------------------------
        */

        $netMargin = $totalRevenue > 0
            ? round(
                ($netProfit / $totalRevenue) * 100
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | 7. DONNÉES PRIX D'ACHAT
        |--------------------------------------------------------------------------
        */

        $hasCostData = $soldItems->contains(function ($item) {

            return (float) (
                $item->variant?->product?->cost_price ?? 0
            ) > 0;
        });


        /*
        |--------------------------------------------------------------------------
        | 8. PRODUITS + STOCK + BÉNÉFICE
        |--------------------------------------------------------------------------
        */

        $products = Product::with([
            'variants',
            'category.parent',
        ])->get();

        $stockValue = 0;

        $stockPotential = 0;

        $allProductStats = [];


        foreach ($products as $product) {

            /*
            |--------------------------------------------------------------------------
            | STOCK
            |--------------------------------------------------------------------------
            */

            $totalStock = (int) $product->variants->sum('stock');


            /*
            |--------------------------------------------------------------------------
            | PRIX D'ACHAT
            |--------------------------------------------------------------------------
            */

            $costPrice = (float) (
                $product->cost_price ?? 0
            );


            /*
            |--------------------------------------------------------------------------
            | PRIX DE VENTE
            |--------------------------------------------------------------------------
            */

            $salePrice = (float) (
                $product->sale_price ?? 0
            );

            $normalPrice = (float) (
                $product->price ?? 0
            );

            $sellingPrice = $salePrice > 0
                ? $salePrice
                : $normalPrice;


            /*
            |--------------------------------------------------------------------------
            | VALEUR STOCK
            |--------------------------------------------------------------------------
            */

            $costValue = $costPrice * $totalStock;

            $sellingValue = $sellingPrice * $totalStock;


            /*
            |--------------------------------------------------------------------------
            | BÉNÉFICE POTENTIEL
            |--------------------------------------------------------------------------
            */

            $profit = $sellingValue - $costValue;


            /*
            |--------------------------------------------------------------------------
            | TOTAUX
            |--------------------------------------------------------------------------
            */

            $stockValue += $costValue;

            $stockPotential += $profit;


            /*
            |--------------------------------------------------------------------------
            | CATÉGORIE PRINCIPALE
            |--------------------------------------------------------------------------
            */

            $category = $product->category;

            if ($category?->parent) {
                $mainCategory = $category->parent;
            } else {
                $mainCategory = $category;
            }


            /*
            |--------------------------------------------------------------------------
            | NOM CATÉGORIE
            |--------------------------------------------------------------------------
            */

            $categoryName = $mainCategory?->name ?? 'Sans catégorie';


            /*
            |--------------------------------------------------------------------------
            | STATISTIQUE PRODUIT
            |--------------------------------------------------------------------------
            */

            if ($costPrice > 0) {

                $margin = $sellingPrice > 0
                    ? round(
                        (($sellingPrice - $costPrice) / $sellingPrice) * 100
                    )
                    : 0;

                $allProductStats[] = [

                    'name' => $product->name,

                    'stock' => $totalStock,

                    'cost_price' => $costPrice,

                    'selling_price' => $sellingPrice,

                    'stock_value' => $costValue,

                    'profit' => $profit,

                    'margin' => $margin,

                    'product' => $product,

                    'category_name' => $categoryName,

                    'category_id' => $mainCategory?->id,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 9. VALEUR DU STOCK PAR CATÉGORIE
        |--------------------------------------------------------------------------
        */

        $mainCategories = [
            'Homme',
            'Femme',
            'Bébé',
            'Enfants',
        ];

        $categoryProductStats = [];


        foreach ($mainCategories as $mainCategoryName) {

            $categoryProductStats[$mainCategoryName] = collect(
                $allProductStats
            )
                ->filter(function ($item) use ($mainCategoryName) {

                    return mb_strtolower(
                        trim($item['category_name'])
                    ) === mb_strtolower(
                        trim($mainCategoryName)
                    );
                })
                ->sortByDesc('stock_value')
                ->take(5)
                ->values()
                ->all();
        }


        /*
        |--------------------------------------------------------------------------
        | 10. TOUS LES PRODUITS
        |--------------------------------------------------------------------------
        */

        usort(
            $allProductStats,
            function ($a, $b) {

                return strcasecmp(
                    $a['name'],
                    $b['name']
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 11. TAUX DE SUCCÈS
        |--------------------------------------------------------------------------
        */

        $successfulOrders = $paidOrders;

        $successRate = $totalOrders > 0
            ? round(
                ($successfulOrders / $totalOrders) * 100
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | 12. TAUX D'ANNULATION
        |--------------------------------------------------------------------------
        */

        $cancellationRate = $totalOrders > 0
            ? round(
                ($cancelledOrders / $totalOrders) * 100
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | 13. PANIER MOYEN
        |--------------------------------------------------------------------------
        */

        $avgOrderValue = (float) (
            Order::where('status', 'paid')->avg('total') ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | 14. DERNIÈRES COMMANDES
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with([
            'user',
        ])
            ->latest()
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 15. NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $unreadNotifications = collect();

        if (auth()->check()) {

            $unreadNotifications = auth()
                ->user()
                ->unreadNotifications()
                ->take(5)
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | 16. STATISTIQUES PRINCIPALES
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total_orders' => $totalOrders,

            'pending_orders' => $pendingOrders,

            'paid_orders' => $paidOrders,

            'total_revenue' => $totalRevenue,

            'total_clients' => $totalClients,

            'new_clients_today' => $newClientsToday,

            'total_products' => $totalProducts,

            'out_of_stock' => $outOfStock,
        ];


        /*
        |--------------------------------------------------------------------------
        | 17. DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', [

            'stats' => $stats,

            'totalOrders' => $totalOrders,

            'pendingOrders' => $pendingOrders,

            'paidOrders' => $paidOrders,

            'paidOrdersCount' => $paidOrders,

            'cancelledOrders' => $cancelledOrders,

            'totalRevenue' => $totalRevenue,

            'totalCostSold' => $totalCostSold,

            'netProfit' => $netProfit,

            'netMargin' => $netMargin,

            'hasCostData' => $hasCostData,

            'stockValue' => $stockValue,

            'stockPotential' => $stockPotential,

            /*
             * مهم:
             * نستعمل categoryProductStats في Dashboard
             */
            'categoryProductStats' => $categoryProductStats,

            /*
             * نحتفظ بـ allProductStats كذلك
             */
            'productStats' => $allProductStats,

            'successRate' => $successRate,

            'cancellationRate' => $cancellationRate,

            'avgOrderValue' => $avgOrderValue,

            'recentOrders' => $recentOrders,

            'unreadNotifications' => $unreadNotifications,
        ]);
    }
}