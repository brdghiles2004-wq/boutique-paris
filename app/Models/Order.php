<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'status', 'subtotal', 'shipping_cost', 'total', 'currency',
        'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_wilaya',
        'shipping_postal_code', 'shipping_country', 'delivery_type',
        'notes', 'guest_email', 'is_guest',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'is_guest' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->order_number ??= 'CMD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}