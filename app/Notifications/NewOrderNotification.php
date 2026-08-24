<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_order',
            'title' => 'Nouvelle commande',
            'message' => "Commande {$this->order->order_number} — {$this->order->total} DA",
            'url' => route('admin.orders.show', $this->order),
            'order_number' => $this->order->order_number,
            'total' => $this->order->total,
        ];
    }
}