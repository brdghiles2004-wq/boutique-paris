<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductProfitController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | PRODUITS
        |--------------------------------------------------------------------------
        */

        $products = Product::with([
            'variants',
            'category.parent',
        ])
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CALCUL DES DONNÉES
        |--------------------------------------------------------------------------
        */

        $productStats = [];

        $stockValue = 0;

        $stockPotential = 0;


        foreach ($products as $product) {

            /*
            |--------------------------------------------------------------------------
            | STOCK TOTAL
            |--------------------------------------------------------------------------
            */

            $totalStock = (int) $product->variants->sum('stock');


            /*
            |--------------------------------------------------------------------------
            | PRIX ACHAT
            |--------------------------------------------------------------------------
            */

            $costPrice = (float) (
                $product->cost_price ?? 0
            );


            /*
            |--------------------------------------------------------------------------
            | PRIX VENTE
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

            $stockCostValue = $costPrice * $totalStock;

            $stockSellingValue = $sellingPrice * $totalStock;


            /*
            |--------------------------------------------------------------------------
            | BÉNÉFICE POTENTIEL
            |--------------------------------------------------------------------------
            */

            $profit = $stockSellingValue - $stockCostValue;


            /*
            |--------------------------------------------------------------------------
            | MARGE
            |--------------------------------------------------------------------------
            */

            $margin = $sellingPrice > 0
                ? round(
                    (($sellingPrice - $costPrice) / $sellingPrice) * 100
                )
                : 0;


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


            $categoryName = $mainCategory?->name
                ?? 'Sans catégorie';


            /*
            |--------------------------------------------------------------------------
            | TOTAUX
            |--------------------------------------------------------------------------
            */

            $stockValue += $stockCostValue;

            $stockPotential += $profit;


            /*
            |--------------------------------------------------------------------------
            | PRODUIT
            |--------------------------------------------------------------------------
            */

            $productStats[] = [

                'name' => $product->name,

                'stock' => $totalStock,

                'cost_price' => $costPrice,

                'selling_price' => $sellingPrice,

                'stock_value' => $stockCostValue,

                'profit' => $profit,

                'margin' => $margin,

                'category_name' => $categoryName,

                'product' => $product,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->get('search', '')
        );

        if ($search !== '') {

            $productStats = array_filter(
                $productStats,
                function ($item) use ($search) {

                    return str_contains(
                        mb_strtolower($item['name']),
                        mb_strtolower($search)
                    )
                    ||
                    str_contains(
                        mb_strtolower($item['category_name']),
                        mb_strtolower($search)
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE CATÉGORIE
        |--------------------------------------------------------------------------
        */

        $categoryFilter = trim(
            (string) $request->get('category', '')
        );

        if ($categoryFilter !== '') {

            $productStats = array_filter(
                $productStats,
                function ($item) use ($categoryFilter) {

                    return mb_strtolower(
                        $item['category_name']
                    ) === mb_strtolower(
                        $categoryFilter
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | A → Z
        |--------------------------------------------------------------------------
        */

        usort(
            $productStats,
            function ($a, $b) {

                return strcasecmp(
                    $a['name'],
                    $b['name']
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CATÉGORIES DISPONIBLES
        |--------------------------------------------------------------------------
        */

        $categories = collect($productStats)
            ->pluck('category_name')
            ->filter()
            ->unique()
            ->sort()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.product-profits.index', [

            'productStats' => $productStats,

            'stockValue' => $stockValue,

            'stockPotential' => $stockPotential,

            'categories' => $categories,

            'search' => $search,

            'categoryFilter' => $categoryFilter,
        ]);
    }
}