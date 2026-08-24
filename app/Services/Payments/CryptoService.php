<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;

class CryptoService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.nowpayments.api_key');
        $this->baseUrl = 'https://api.nowpayments.io/v1';
    }

    /** تحويل DZD لـ USD بالسعر الحالي */
    private function convertDzdToUsdt(float $amountDzd): float
    {
        // السعر اللي تحب تعتمد عليه
        $usdtRate = 248.50;
    
        return round($amountDzd / $usdtRate, 6);
    }

    /** خلق فاتورة دفع جديدة */
    public function createPayment(array $order): array
    {
        // تحويل المبلغ من DZD لـ USD
        $amountUsdt = $this->convertDzdToUsdt((float) $order['total']);

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/payment", [
           'price_amount' => $amountUsdt,
            'price_currency' => 'usd',
            'pay_currency' => 'usdttrc20',
            'order_id' => $order['order_number'],
            'order_description' => "Commande {$order['order_number']} — Boutique Paris",
            'ipn_callback_url' => route('payment.crypto.webhook'),
            'success_url' => route('payment.crypto.success'),
            'cancel_url' => route('checkout.index'),
        ]);

        if ($response->failed()) {
            throw new \Exception('NowPayments API error: ' . $response->body());
        }

        return $response->json();
    }

    /** التحقق من حالة الدفع */
    public function getPaymentStatus(string $paymentId): array
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->get("{$this->baseUrl}/payment/{$paymentId}");

        return $response->json();
    }

    /** التحقق من صحة IPN webhook */
    public function verifyWebhook(string $payload, string $signature): bool
    {
        $secret = config('services.nowpayments.ipn_secret');
        if (! $secret) return true;

        $expectedSig = hash_hmac('sha512', $payload, $secret);
        return hash_equals($expectedSig, $signature);
    }
}