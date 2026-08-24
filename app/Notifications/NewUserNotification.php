<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    public function __construct(public User $user) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_user',
            'title' => 'Nouvel inscrit',
            'message' => "{$this->user->name} vient de s'inscrire",
            'url' => route('admin.users.index'),
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
        ];
    }
}