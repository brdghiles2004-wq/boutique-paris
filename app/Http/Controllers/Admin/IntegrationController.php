<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    public function index(): View
    {
        return view('admin.integrations.index');
    }

    // ========== DELIVERY ==========
    public function delivery(): View
    {
        return view('admin.integrations.delivery');
    }

    public function saveDelivery(Request $request): RedirectResponse
    {
        $keys = [
            'yalidine_api_id', 'yalidine_api_token', 'yalidine_webhook_url', 'yalidine_mode',
            'yalidine_enabled',
            'zr_express_key', 'zr_express_secret', 'zr_express_webhook',
            'zr_express_enabled',
            'maystro_api_key', 'maystro_token', 'maystro_webhook',
            'maystro_enabled',
            'ecotrack_username', 'ecotrack_password', 'ecotrack_api_key', 'ecotrack_webhook',
            'ecotrack_enabled',
        ];

        $this->saveKeys($request, $keys, 'delivery');
        return back()->with('success', 'Livraison enregistrée ✅');
    }

    // ========== PAYMENT ==========
    public function payment(): View
    {
        return view('admin.integrations.payment');
    }

    public function savePayment(Request $request): RedirectResponse
    {
        $keys = [
            // Crypto (NowPayments)
            'crypto_enabled',
            'nowpayments_api_key', 'nowpayments_public_key',
            'nowpayments_ipn_secret', 'nowpayments_sandbox',
            'crypto_webhook_secret',
            // SATIM
            'satim_enabled', 'satim_merchant_id', 'satim_terminal_id',
            'satim_api_key', 'satim_secret_key', 'satim_webhook', 'satim_sandbox',
            // PayPal
            'paypal_enabled', 'paypal_client_id', 'paypal_client_secret', 'paypal_sandbox',
            // RedotPay
            'redotpay_api_key', 'redotpay_sandbox',
            // Manual virements
            'baridimob_enabled', 'baridimob_holder', 'baridimob_rib',
            'cib_enabled', 'cib_holder', 'cib_rib',
            'wise_enabled', 'wise_holder', 'wise_email',
            'mastercard_enabled', 'mastercard_holder', 'mastercard_rib',
            // Banques algériennes
            'bank_satim_enabled', 'bank_satim_holder', 'bank_satim_rib',
            'bank_cpa_enabled',   'bank_cpa_holder',   'bank_cpa_rib',
            'bank_bdl_enabled',   'bank_bdl_holder',   'bank_bdl_rib',
            'bank_badr_enabled',  'bank_badr_holder',  'bank_badr_rib',
            'bank_bna_enabled',   'bank_bna_holder',   'bank_bna_rib',
            'bank_agb_enabled',   'bank_agb_holder',   'bank_agb_rib',
            'bank_sg_enabled',    'bank_sg_holder',    'bank_sg_rib',
        ];

        $this->saveKeys($request, $keys, 'payment');
        return back()->with('success', 'Paiement enregistré ✅');
    }

    // ========== MARKETING ==========
    public function marketing(): View
    {
        return view('admin.integrations.marketing');
    }

    public function saveMarketing(Request $request): RedirectResponse
    {
        $keys = [
            'fb_pixel_id', 'fb_pixel_enabled',
            'meta_access_token', 'meta_page_id', 'meta_capi_enabled',
            'ga_measurement_id', 'ga_enabled',
            'gtm_id', 'gtm_enabled',
            'google_ads_id', 'google_ads_label', 'google_ads_enabled',
            'tiktok_pixel_id', 'tiktok_access_token', 'tiktok_pixel_enabled',
        ];

        $this->saveKeys($request, $keys, 'marketing');
        return back()->with('success', 'Marketing enregistré ✅');
    }

    // ========== EMAIL ==========
    public function email(): View
    {
        return view('admin.integrations.email');
    }

    public function saveEmail(Request $request): RedirectResponse
    {
        $keys = [
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
            'smtp_encryption', 'smtp_from_name', 'smtp_from_email', 'smtp_enabled',
        ];

        $this->saveKeys($request, $keys, 'email');
        return back()->with('success', 'Email enregistré ✅');
    }

    // ========== COMMUNICATION ==========
    public function communication(): View
    {
        return view('admin.integrations.communication');
    }

    public function saveCommunication(Request $request): RedirectResponse
    {
        $keys = ['whatsapp_number', 'whatsapp_enabled', 'whatsapp_message'];
        $this->saveKeys($request, $keys, 'communication');
        return back()->with('success', 'Communication enregistrée ✅');
    }

    // ========== SEO ==========
    public function seo(): View
    {
        return view('admin.integrations.seo');
    }

    public function saveSeo(Request $request): RedirectResponse
    {
        $keys = [
            'gsc_verification', 'meta_title', 'meta_description',
            'meta_keywords', 'og_image',
        ];

        $this->saveKeys($request, $keys, 'seo');
        return back()->with('success', 'SEO enregistré ✅');
    }

    // ========== OAUTH & APP ==========
    public function oauth(): View
    {
        return view('admin.integrations.oauth');
    }

    public function saveOauth(Request $request): RedirectResponse
    {
        $keys = [
            'google_client_id', 'google_client_secret', 'google_redirect_uri',
            'app_url',
        ];

        $this->saveKeys($request, $keys, 'oauth');
        return back()->with('success', 'OAuth & App URL enregistrés ✅');
    }

    // ========== HELPER PRIVÉ ==========
    private function saveKeys(Request $request, array $keys, string $group): void
    {
        $data = [];
        foreach ($keys as $key) {
            $isBool = str_ends_with($key, '_enabled') || str_ends_with($key, '_sandbox');
            $data[$key] = $isBool
                ? ($request->boolean($key) ? '1' : '0')
                : $request->input($key, '');
        }
        Setting::setMany($data, $group);
    }
}