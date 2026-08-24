<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_en', 'name_ar',
        'slug',
        'description', 'description_en', 'description_ar',
        'price', 'sale_price', 'cost_price',
        'image', 'category_id',
        'is_active', 'is_featured',
    ];
    
    public function getTransNameAttribute(): string
    {
        return match(app()->getLocale()) {
            'ar' => $this->name_ar ?: $this->name,
            'en' => $this->name_en ?: $this->name,
            default => $this->name,
        };
    }
    
    public function getTransDescriptionAttribute(): string
    {
        return match(app()->getLocale()) {
            'ar' => $this->description_ar ?: ($this->description ?? ''),
            'en' => $this->description_en ?: ($this->description ?? ''),
            default => $this->description ?? '',
        };
    }
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getFinalPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->variants->sum('stock');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function scopeLowStock($query, int $threshold = 5)
{
    return $query->whereHas('variants', fn($q) => $q->where('stock', '<=', $threshold)->where('stock', '>', 0));
}
}