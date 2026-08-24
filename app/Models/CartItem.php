<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_variant_id', 'quantity'];

    // ===== RELATIONS =====
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // ===== ACCESSORS =====
    public function getSubtotalAttribute(): float
    {
        $price = (float) (
            $this->variant?->product?->sale_price
            ?? $this->variant?->product?->price
            ?? 0
        );
        return $price * $this->quantity;
    }

    public function getTotalAttribute(): float
    {
        return $this->subtotal;
    }

    public function getProductNameAttribute(): string
    {
        return $this->variant?->product?->trans_name
            ?? $this->variant?->product?->name
            ?? 'Produit';
    }

    public function getPriceAttribute(): float
    {
        return (float) (
            $this->variant?->product?->sale_price
            ?? $this->variant?->product?->price
            ?? 0
        );
    }
}