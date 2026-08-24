<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $this->configureApp();
        $this->configureMail();
        $this->configureNowPayments();
        $this->configureGoogle();
        $this->configureRedotPay();
    }

    private function configureApp(): void
    {
        $appUrl = \App\Models\Setting::get('app_url');
        if ($appUrl) {
            config(['app.url' => $appUrl]);
            URL::forceRootUrl($appUrl);
            if (str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }
    }

    private function configureMail(): void
    {
        $enabled  = \App\Models\Setting::get('smtp_enabled') == '1';
        $host     = \App\Models\Setting::get('smtp_host');
        $username = \App\Models\Setting::get('smtp_username');

        if ($enabled && $host && $username) {
            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => (int) \App\Models\Setting::get('smtp_port', 587),
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => \App\Models\Setting::get('smtp_password'),
                'mail.mailers.smtp.encryption' => \App\Models\Setting::get('smtp_encryption', 'tls'),
                'mail.from.address'            => \App\Models\Setting::get('smtp_from_email', $username),
                'mail.from.name'               => \App\Models\Setting::get('smtp_from_name', 'Boutique Paris'),
            ]);
        }
    }

    private function configureNowPayments(): void
    {
        $apiKey = \App\Models\Setting::get('nowpayments_api_key');
        if ($apiKey) {
            config([
                'services.nowpayments.api_key'    => $apiKey,
                'services.nowpayments.public_key' => \App\Models\Setting::get('nowpayments_public_key'),
                'services.nowpayments.ipn_secret' => \App\Models\Setting::get('nowpayments_ipn_secret'),
                'services.nowpayments.sandbox'    => \App\Models\Setting::get('nowpayments_sandbox', '1') == '1',
                'services.nowpayments.webhook'    => \App\Models\Setting::get('crypto_webhook_secret'),
            ]);
        }
    }

    private function configureGoogle(): void
    {
        $clientId = \App\Models\Setting::get('google_client_id');
        if ($clientId) {
            config([
                'services.google.client_id'     => $clientId,
                'services.google.client_secret' => \App\Models\Setting::get('google_client_secret'),
                'services.google.redirect'      => \App\Models\Setting::get('google_redirect_uri',
                    url('/auth/google/callback')),
            ]);
        }
    }

    private function configureRedotPay(): void
    {
        $apiKey = \App\Models\Setting::get('redotpay_api_key');
        if ($apiKey) {
            config([
                'services.redotpay.api_key' => $apiKey,
                'services.redotpay.sandbox' => \App\Models\Setting::get('redotpay_sandbox', '1') == '1',
            ]);
        }
    }
}