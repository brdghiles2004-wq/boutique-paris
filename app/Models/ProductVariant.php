<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_hex',
        'stock',
        'extra_price',
        'sku',
    ];

    protected $casts = [
        'stock' => 'integer',
        'extra_price' => 'float',
    ];

    // =========================================================
    // RELATION
    // =========================================================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // =========================================================
    // STOCK
    // =========================================================

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    // =========================================================
    // LABEL
    // =========================================================

    public function getLabelAttribute(): string
    {
        $parts = [];

        if ($this->size) {
            $parts[] = $this->size;
        }

        if ($this->color) {
            $parts[] = $this->color;
        }

        return implode(' / ', $parts) ?: 'Unique';
    }

    // =========================================================
    // PRICE
    // =========================================================

    /**
     * السعر الأساسي للـ variant
     *
     * السعر يأتي من المنتج:
     *
     * sale_price إذا موجود
     * وإلا price
     *
     * ملاحظة:
     * extra_price لا يتم استعماله هنا حتى لا يتضاعف السعر.
     */
    public function getPriceAttribute(): float
    {
        if (!$this->product) {
            return 0.0;
        }

        if (
            $this->product->sale_price !== null &&
            $this->product->sale_price > 0
        ) {
            return (float) $this->product->sale_price;
        }

        return (float) ($this->product->price ?? 0);
    }

    // =========================================================
    // FINAL PRICE
    // =========================================================

    /**
     * السعر النهائي الذي يستعمله Checkout
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->price;
    }
}