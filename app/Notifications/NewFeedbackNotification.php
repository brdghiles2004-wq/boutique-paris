<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFeedbackNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $name,
        public string $email,
        public string $subject,
        public string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_feedback',
            'title' => 'Nouveau message Support',
            'message' => "{$this->name} — {$this->subject}",
            'url' => route('admin.notifications.index'),
            'from_name' => $this->name,
            'from_email' => $this->email,
        ];
    }
}