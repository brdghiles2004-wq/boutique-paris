<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\Payments\CryptoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PaymentController extends Controller
{
    // ================================================================
    // REGISTRE CENTRAL: tous les modes de paiement
    // ================================================================
    private function getPaymentRegistry(Order $order): array
    {
        return [
            // ===== AUTOMATIQUES =====
            [
                'key'         => 'crypto',
                'type'        => 'auto',
                'icon'        => '🪙',
                'name'        => 'Crypto',
                'subtitle'    => 'Bitcoin • USDT • Ethereum +300',
                'badge'       => '✓ Automatique — NOWPayments',
                'badge_color' => 'text-green-400',
                'enabled'     => ! empty(config('services.nowpayments.api_key')),
                'route'       => route('payment.crypto', $order),
            ],
            // ===== DIRECT =====
            [
                'key'         => 'cod',
                'type'        => 'direct',
                'icon'        => '💵',
                'name'        => 'Paiement à la livraison',
                'subtitle'    => 'Cash on Delivery',
                'badge'       => '✓ Payez à la réception du colis',
                'badge_color' => 'text-green-400',
                'enabled'     => true,
                'route'       => route('payment.cod', $order),
            ],
            // ===== MANUELS =====
            [
                'key'         => 'baridimob',
                'type'        => 'manual',
                'icon'        => '📱',
                'name'        => 'BaridiMob',
                'subtitle'    => 'Algérie Poste — CCP',
                'badge'       => '✓ Virement CCP',
                'badge_color' => 'text-[#C9A24B]',
                'enabled'     => Setting::get('baridimob_enabled') == '1',
                'rib'         => Setting::get('baridimob_rib', ''),
                'holder'      => Setting::get('baridimob_holder', 'Boutique Paris'),
            ],
            [
                'key'         => 'cpa',
                'type'        => 'manual',
                'icon'        => '🏛️',
                'name'        => 'CPA e-Payment',
                'subtitle'    => 'Crédit Populaire d\'Algérie',
                'badge'       => '✓ Virement CPA',
                'badge_color' => 'text-[#C9A24B]',
                'enabled'     => Setting::get('bank_cpa_enabled') == '1',
                'rib'         => Setting::get('bank_cpa_rib', ''),
                'holder'      => Setting::get('bank_cpa_holder', 'Boutique Paris'),
            ],
            [
                'key'         => 'bdl',
                'type'        => 'manual',
                'icon'        => '🏢',
                'name'        => 'BDL e-Payment',
                'subtitle'    => 'Banque de Développement Local',
                'badge'       => '✓ Virement BDL',
                'badge_color' => 'text-[#C9A24B]',
                'enabled'     => Setting::get('bank_bdl_enabled') == '1',
                'rib'         => Setting::get('bank_bdl_rib', ''),
                'holder'      => Setting::get('bank_bdl_holder', 'Boutique Paris'),
            ],
            [
                'key'         => 'badr',
                'type'        => 'manual',
                'icon'        => '🌾',
                'name'        => 'BADR e-Payment',
                'subtitle'    => 'Banque de l\'Agriculture',
                'badge'       => '✓ Virement BADR',
                'badge_color' => 'text-[#C9A24B]',
                'enabled'     => Setting::get('bank_badr_enabled') == '1',
                'rib'         => Setting::get('bank_badr_rib', ''),
                'holder'      => Setting::get('bank_badr_holder', 'Boutique Paris'),
            ],
            [
                'key'         => 'bna',
                'type'        => 'manual',
                'icon'        => '🏦',
                'name'        => 'BNA e-Payment',
                'subtitle'    => 'Banque Nationale d\'Algérie',
                'badge'       => '✓ Virement BNA',
                'badge_color' => 'text-[#C9A24B]',
                'enabled'     => Setting::get('bank_bna_enabled') == '1',
                'rib'         => Setting::get('bank_bna_rib', ''),
                'holder'      => Setting::get('bank_bna_holder', 'Boutique Paris'),
            ],
            [
                'key'         => 'agb',
                'type'        => 'manual',
                'icon'        => '🌍',
                'name'        => 'AGB',
                'subtitle'    => 'Visa • Mastercard',
                'badge'       => '✓ Virement AGB',
                'badge_color' => 'text-[#C9A24B]',
                'enabled'     => Setting::get('bank_agb_enabled') == '1',
                'rib'         => Setting::get('bank_agb_rib', ''),
                'holder'      => Setting::get('bank_agb_holder', 'Boutique Paris'),
            ],
            [
                'key'         => 'sg',
                'type'        => 'manual',
                'icon'        => '🔴',
                'name'        => 'Société Générale Algérie',
                'subtitle'    => 'SGA • CIB • EDAHABIA',
                'badge'       => '✓ Virement SGA',
                'badge_color' => 'text-[#C9A24B]',
                'enabled'     => Setting::get('bank_sg_enabled') == '1',
                'rib'         => Setting::get('bank_sg_rib', ''),
                'holder'      => Setting::get('bank_sg_holder', 'Boutique Paris'),
            ],
        ];
    }

    // ================================================================
    // SHOW: page choix paiement
    // ================================================================
    public function show(Order $order): View|RedirectResponse
    {
        if (Auth::check() && $order->user_id !== null && $order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        $registry = $this->getPaymentRegistry($order);

        // Séparer actifs et inactifs
        $activeMethods   = array_values(array_filter($registry, fn($m) => $m['enabled']));
        $inactiveMethods = array_values(array_filter($registry, fn($m) => ! $m['enabled']));

        return view('shop.payment', compact('order', 'activeMethods', 'inactiveMethods'));
    }

    // ================================================================
    // CRYPTO
    // ================================================================
    public function payCrypto(Order $order): View|RedirectResponse
    {
        if (Auth::check() && $order->user_id !== null && $order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        try {
            $crypto      = new CryptoService();
            $paymentData = $crypto->createPayment($order->toArray());

            Payment::create([
                'order_id'       => $order->id,
                'gateway'        => 'crypto',
                'transaction_id' => $paymentData['payment_id'],
                'amount'         => $order->total,
                'currency'       => 'USD',
                'status'         => 'pending',
                'raw_response'   => $paymentData,
            ]);

            return view('shop.crypto-payment', compact('order', 'paymentData'));

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur Crypto: ' . $e->getMessage());
        }
    }

    public function cryptoWebhook(Request $request): Response
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-nowpayments-sig', '');
        $crypto    = new CryptoService();

        if (! $crypto->verifyWebhook($payload, $signature)) {
            return response('Unauthorized', 401);
        }

        $data    = $request->json()->all();
        $payment = Payment::where('transaction_id', $data['payment_id'])->first();

        if ($payment && in_array($data['payment_status'], ['finished', 'confirmed'])) {
            $payment->markCompleted($data['payment_id']);
        }

        return response('OK', 200);
    }

    public function cryptoSuccess(): View
    {
        return view('shop.payment-success');
    }

    // ================================================================
    // SATIM (API)
    // ================================================================
    public function paySatim(Order $order): RedirectResponse
    {
        if (Auth::check() && $order->user_id !== null && $order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        $merchantId = Setting::get('satim_merchant_id');
        $apiKey     = Setting::get('satim_api_key');
        $isSandbox  = Setting::get('satim_sandbox', '1') == '1';
        $baseUrl    = $isSandbox
            ? 'https://test.satim.dz/payment/rest'
            : 'https://satim.dz/payment/rest';

        if (! $merchantId || ! $apiKey) {
            return back()->with('error', 'SATIM non configuré. Contactez l\'administrateur.');
        }

        try {
            $response = Http::post("{$baseUrl}/register.do", [
                'userName'    => $merchantId,
                'password'    => $apiKey,
                'orderNumber' => $order->order_number,
                'amount'      => $order->total * 100,
                'currency'    => '012',
                'returnUrl'   => route('payment.satim.callback'),
                'language'    => 'fr',
                'description' => "Boutique Paris — {$order->order_number}",
            ]);

            $data = $response->json();

            if (isset($data['errorCode']) && $data['errorCode'] != '0') {
                throw new \Exception($data['errorMessage'] ?? 'Erreur SATIM');
            }

            Payment::create([
                'order_id'       => $order->id,
                'gateway'        => 'satim',
                'transaction_id' => $data['orderId'] ?? null,
                'amount'         => $order->total,
                'currency'       => 'DZD',
                'status'         => 'pending',
                'raw_response'   => $data,
            ]);

            return redirect($data['formUrl']);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur SATIM: ' . $e->getMessage());
        }
    }

    public function satimCallback(Request $request): View|RedirectResponse
    {
        $orderId    = $request->get('orderId');
        $merchantId = Setting::get('satim_merchant_id');
        $apiKey     = Setting::get('satim_api_key');
        $isSandbox  = Setting::get('satim_sandbox', '1') == '1';
        $baseUrl    = $isSandbox
            ? 'https://test.satim.dz/payment/rest'
            : 'https://satim.dz/payment/rest';

        if (! $orderId) {
            return redirect()->route('home')->with('error', 'Paiement annulé.');
        }

        $response = Http::post("{$baseUrl}/confirmOrder.do", [
            'userName' => $merchantId,
            'password' => $apiKey,
            'orderId'  => $orderId,
            'language' => 'fr',
        ]);

        $data    = $response->json();
        $payment = Payment::where('transaction_id', $orderId)->first();

        if ($payment && ($data['errorCode'] ?? '1') == '0') {
            $payment->markCompleted($orderId);
            return view('shop.payment-success');
        }

        return redirect()->route('home')->with('error', 'Paiement non confirmé.');
    }

    // ================================================================
    // PAYPAL
    // ================================================================
    public function payPaypal(Order $order): RedirectResponse
    {
        return back()->with('error', 'PayPal en cours de configuration.');
    }

    // ================================================================
    // COD
    // ================================================================
    public function payCod(Order $order): RedirectResponse
    {
        if (Auth::check() && $order->user_id !== null && $order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        Payment::create([
            'order_id' => $order->id,
            'gateway'  => 'cod',
            'amount'   => $order->total,
            'currency' => 'DZD',
            'status'   => 'pending',
        ]);

        $order->update(['status' => 'processing']);

        return redirect()->route('payment.manual.success', $order)
            ->with('success', 'Commande confirmée — Paiement à la livraison ✅');
    }

    // ================================================================
    // MANUAL (virement + preuve)
    // ================================================================
    public function payManual(Request $request, Order $order): RedirectResponse
    {
        if (Auth::check() && $order->user_id !== null && $order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        $request->validate([
            'gateway'     => 'required|string',
            'proof_image' => 'nullable|image|max:3048',
            'proof_notes' => 'nullable|string|max:500',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('payments/proofs', 'public');
        }

        Payment::create([
            'order_id'    => $order->id,
            'gateway'     => $request->gateway,
            'amount'      => $order->total,
            'currency'    => 'DZD',
            'status'      => 'pending',
            'proof_image' => $proofPath,
            'proof_notes' => $request->proof_notes,
        ]);

        $order->update(['status' => 'processing']);

        return redirect()->route('payment.manual.success', $order)
            ->with('success', 'Preuve de paiement envoyée ✅ Votre commande sera confirmée sous 24h.');
    }

    public function manualSuccess(Order $order): View
    {
        return view('shop.payment-manual-success', compact('order'));
    }
}