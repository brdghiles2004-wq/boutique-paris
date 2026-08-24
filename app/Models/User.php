<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'phone', 'address', 'city', 'postal_code', 'country', 'avatar', 'google_id',
        'is_admin',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cart(): HasMany
{
    return $this->hasMany(Cart::class);
}

public function getAvatarUrlAttribute()
{
    if ($this->avatar && file_exists(public_path('storage/' . $this->avatar))) {
        return asset('storage/' . $this->avatar);
    }

    return asset('images/default-avatar.png');
}
}