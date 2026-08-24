<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = [
        'name', 'email', 'subject', 'message',
        'is_read', 'read_at', 'reply', 'replied_at',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'read_at'    => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function markAsRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    public function isReplied(): bool
    {
        return ! is_null($this->replied_at);
    }
}