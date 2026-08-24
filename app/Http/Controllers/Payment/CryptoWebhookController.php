<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CryptoWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();

        // 1) التحقق من الـ Signature
        $secret = config('services.crypto.webhook_secret');
        if ($secret) {
            $signature = $request->header('x-nowpayments-sig', '');
            $data = json_decode($payload, true);
            ksort($data);
            $expected = hash_hmac('sha512', json_encode($data), $secret);

            if (! hash_equals($expected, $signature)) {
                Log::warning('Crypto webhook: signature invalide');
                return response('Unauthorized', 401);
            }
        }

        // 2) استقبال البيانات
        $data = $request->json()->all();

        if (empty($data['payment_id']) || empty($data['payment_status'])) {
            return response('Bad Request', 400);
        }

        // 3) البحث عن الـ Payment
        $payment = Payment::where('transaction_id', $data['payment_id'])->first();

        if (! $payment) {
            Log::warning('Crypto webhook: payment introuvable', ['payment_id' => $data['payment_id']]);
            return response('Not Found', 404);
        }

        $order = $payment->order;

        if (! $order) {
            return response('Order Not Found', 404);
        }

        // 4) التحقق من المبلغ (تحقق بسيط: الفرق أقل من 1%)
        $paidAmount = (float) ($data['actually_paid'] ?? $data['amount_received'] ?? 0);
        $expectedAmount = (float) $payment->amount;

        if ($paidAmount > 0 && $expectedAmount > 0) {
            $diff = abs($paidAmount - $expectedAmount) / $expectedAmount;
            if ($diff > 0.01) {
                Log::warning('Crypto webhook: montant incorrect', [
                    'expected' => $expectedAmount,
                    'received' => $paidAmount,
                    'order' => $order->order_number,
                ]);
            }
        }

        // 5) تحديث الـ Payment والـ Order حسب الحالة
        $status = $data['payment_status'];

        match ($status) {
            'finished', 'confirmed' => $this->markPaid($payment, $order, $data),
            'failed', 'expired'     => $this->markFailed($payment, $order),
            'partially_paid'        => $this->markPartial($payment, $data),
            default                 => null,
        };

        // تسجيل الـ webhook فالـ log
        Log::info('Crypto webhook reçu', [
            'status'     => $status,
            'payment_id' => $data['payment_id'],
            'order'      => $order->order_number,
        ]);

        return response('OK', 200);
    }

    private function markPaid(Payment $payment, Order $order, array $data): void
    {
        $payment->update([
            'status'       => 'completed',
            'raw_response' => $data,
        ]);

        $order->update(['status' => 'paid']);
    }

    private function markFailed(Payment $payment, Order $order): void
    {
        $payment->update(['status' => 'failed']);
        $order->update(['status' => 'cancelled']);
    }

    private function markPartial(Payment $payment, array $data): void
    {
        $payment->update([
            'status'       => 'pending',
            'raw_response' => $data,
        ]);
    }
}