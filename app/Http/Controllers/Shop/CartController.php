<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->currentCart();
        $cart->load('items.variant.product');

        return view('shop.cart', compact('cart'));
    }

    public function add(Request $request, ProductVariant $variant): RedirectResponse
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = $request->input('quantity', 1);
        $cart = $this->currentCart();

        $item = $cart->items()->where('product_variant_id', $variant->id)->first();

        if ($item) {
            $item->update(['quantity' => min($item->quantity + $quantity, $variant->stock)]);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => min($quantity, $variant->stock),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier ✅');
    }

    public function update(Request $request, CartItem $item): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max($item->variant->stock, 1)],
        ]);

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantité mise à jour');
    }

    public function remove(CartItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('success', 'Produit retiré du panier');
    }

    private function currentCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        return Cart::firstOrCreate([
            'session_id' => session()->getId(),
            'user_id' => null,
        ]);
    }
}