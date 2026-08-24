<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // ============================================================
        // CATÉGORIES PRINCIPALES
        // ============================================================

        $categories = Category::main()
            ->with('children')
            ->orderBy('order')
            ->get();


        // ============================================================
        // PRODUITS VEDETTES PAR CATÉGORIE
        // ============================================================
        // كل Produit فيه:
        //
        // is_featured = true
        //
        // يبان تلقائياً في Home.
        //
        // ماكان حتى limit:
        // 4 produits  → يبانوا 4
        // 10 produits → يبانوا 10
        // 50 produits → يبانوا 50
        // 100 produits → يبانوا 100
        // ============================================================

        $featuredByCategory = $categories
            ->mapWithKeys(function ($category) {

                // IDs تاع catégorie principale + sous-catégories
                $categoryIds = $category->children
                    ->pluck('id')
                    ->push($category->id);

                // جميع المنتجات النشطة والمميزة
                // بدون take() أو limit()
                $products = Product::active()
                    ->where('is_featured', true)
                    ->whereIn('category_id', $categoryIds)
                    ->with([
                        'category',
                        'variants'
                    ])
                    ->get();

                return [
                    $category->id => [
                        'category' => $category,
                        'products' => $products,
                    ],
                ];
            })

            // نخلي غير les catégories اللي عندها produits vedettes
            ->filter(
                fn ($item) => $item['products']->isNotEmpty()
            );


        // ============================================================
        // RETOUR HOME
        // ============================================================

        return view(
            'shop.home',
            compact(
                'categories',
                'featuredByCategory'
            )
        );
    }
}