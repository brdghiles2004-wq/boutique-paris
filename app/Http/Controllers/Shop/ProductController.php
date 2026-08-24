<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Category $category): View
    {
        // Load children للكاتيقوري
        $category->load('children');

        // نجيب IDs: الكاتيقوري + كل أولادها
        $categoryIds = collect([$category->id])
            ->merge($category->children->pluck('id'));

        // كل المنتجات
        $products = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->with(['variants', 'category'])
            ->latest()
            ->paginate(24);

        // Sidebar
        if ($category->parent_id) {
            // sous-catégorie → نجيب الـ parent مع أولاده
            $parentCat    = Category::with('children')->find($category->parent_id);
            $sidebarLinks = $parentCat->children;
        } else {
            // catégorie principale → أولادها هم الـ sidebar
            $parentCat    = $category;
            $sidebarLinks = $category->children;
        }

        return view('shop.category', compact(
            'category', 'products', 'parentCat', 'sidebarLinks'
        ));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['variants', 'category'])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }
}