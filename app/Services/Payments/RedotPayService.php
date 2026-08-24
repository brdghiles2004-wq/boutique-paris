<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;

class RedotPayService
{
    private ?string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.redotpay.api_key');
        $this->baseUrl = config('services.redotpay.sandbox')
            ? 'https://sandbox-api.redotpay.com/v1'
            : 'https://api.redotpay.com/v1';
    }

    public function createPayment(array $order): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/payments", [
            'amount'       => $order['total'],
            'currency'     => 'USD',
            'order_id'     => $order['order_number'],
            'description'  => "Boutique Paris — {$order['order_number']}",
            'redirect_url' => route('payment.redotpay.success'),
            'cancel_url'   => route('payment.show', ['order' => $order['id']]),
            'webhook_url'  => route('payment.redotpay.webhook'),
        ]);

        if ($response->failed()) {
            throw new \Exception('RedotPay API error: ' . $response->body());
        }

        return $response->json();
    }
}