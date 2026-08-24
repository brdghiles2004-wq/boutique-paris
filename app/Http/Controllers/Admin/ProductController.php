<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $stock = request('stock');

        $query = Product::with(['category', 'variants'])->latest();

        if ($stock === 'low') {
            $query->whereHas('variants', fn($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5));
        } elseif ($stock === 'out') {
            $query->whereDoesntHave('variants', fn($q) => $q->where('stock', '>', 0));
        }

        $products = $query->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'cost_price'     => 'nullable|numeric|min:0',
            'is_active'      => 'nullable',
            'is_featured'    => 'nullable',
            'image'          => 'nullable|image|max:2048',
            'variants'       => 'nullable|array',
        ]);

        $validated['slug']        = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['cost_price']  = $request->input('cost_price') ?: null;
        $validated['sale_price']  = $request->input('sale_price') ?: null;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create(\Illuminate\Support\Arr::except($validated, ['variants']));

        // Variants
        if ($request->has('variants')) {
            foreach ($request->variants as $v) {
                if (empty($v['size']) && empty($v['color']) && ! isset($v['stock'])) continue;
                $product->variants()->create([
                    'size'        => $v['size']        ?? null,
                    'color'       => $v['color']       ?? null,
                    'color_hex'   => $v['color_hex']   ?? '#1a1a1a',
                    'stock'       => (int) ($v['stock'] ?? 0),
                    'extra_price' => (float) ($v['extra_price'] ?? 0),
                    'sku'         => strtoupper(Str::random(8)),
                ]);
            }
        }

        // Variant par défaut si aucun
        if ($product->variants()->count() === 0) {
            $product->variants()->create([
                'size'        => 'Unique',
                'color'       => null,
                'stock'       => 10,
                'extra_price' => 0,
                'sku'         => strtoupper(Str::random(8)),
            ]);
        }

        Cache::flush();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé ✅');
    }

    public function edit(Product $product): View
    {
        $product->load('variants');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'cost_price'     => 'nullable|numeric|min:0',
            'is_active'      => 'nullable',
            'is_featured'    => 'nullable',
            'image'          => 'nullable|image|max:2048',
            'variants'       => 'nullable|array',
        ]);

        $validated['is_active']   = $request->input('is_active', '0') == '1';
        $validated['is_featured'] = $request->input('is_featured', '0') == '1';
        $validated['cost_price']  = $request->input('cost_price') ?: null;
        $validated['sale_price']  = $request->input('sale_price') ?: null;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update(\Illuminate\Support\Arr::except($validated, ['variants']));

        // Mise à jour variants
        if ($request->has('variants')) {
            $existingIds = [];
            foreach ($request->variants as $v) {
                if (!empty($v['id'])) {
                    $variant = $product->variants()->find($v['id']);
                    if ($variant) {
                        $variant->update([
                            'size'        => $v['size']        ?? null,
                            'color'       => $v['color']       ?? null,
                            'color_hex'   => $v['color_hex']   ?? '#1a1a1a',
                            'stock'       => (int) ($v['stock'] ?? 0),
                            'extra_price' => (float) ($v['extra_price'] ?? 0),
                        ]);
                        $existingIds[] = $variant->id;
                    }
                } else {
                    if (!empty($v['size']) || !empty($v['color']) || isset($v['stock'])) {
                        $new = $product->variants()->create([
                            'size'        => $v['size']        ?? null,
                            'color'       => $v['color']       ?? null,
                            'color_hex'   => $v['color_hex']   ?? '#1a1a1a',
                            'stock'       => (int) ($v['stock'] ?? 0),
                            'extra_price' => (float) ($v['extra_price'] ?? 0),
                            'sku'         => strtoupper(Str::random(8)),
                        ]);
                        $existingIds[] = $new->id;
                    }
                }
            }
            $product->variants()->whereNotIn('id', $existingIds)->delete();
        }

        // Auto-désactiver si stock = 0
        $totalStock = $product->variants()->sum('stock');
        if ($totalStock === 0) {
            $product->update(['is_active' => false]);
        }

        Cache::flush();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour ✅');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->variants()->delete();
        $product->delete();

        Cache::flush();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé');
    }

    public function updateStock(Request $request, \App\Models\ProductVariant $variant): \Illuminate\Http\JsonResponse
    {
        $request->validate(['stock' => 'required|integer|min:0']);

        $variant->update(['stock' => $request->stock]);

        $product    = $variant->product;
        $totalStock = $product->variants()->sum('stock');

        if ($totalStock === 0 && $product->is_active) {
            $product->update(['is_active' => false]);
            Cache::flush();
            return response()->json(['status' => 'deactivated', 'total_stock' => 0]);
        }

        Cache::flush();
        return response()->json(['status' => 'updated', 'total_stock' => $totalStock]);
    }
}