@extends('admin.layouts.app')
@section('title', 'Paiement')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.integrations.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">💳 Paiement</h1>
    </div>

    <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] mb-8 bg-[#C9A24B]/5 border border-[#C9A24B]/20 rounded-xl px-5 py-3 max-w-2xl">
        ✅ Tout est géré depuis cette page — aucune modification du fichier <code>.env</code> n'est nécessaire.
    </p>

    <form action="{{ route('admin.integrations.payment.save') }}" method="POST"
          class="space-y-4 max-w-2xl" x-data="{ open: null }">
        @csrf

        @php
            $sections = [
                [
                    'title'   => 'Automatiques (API)',
                    'color'   => 'text-green-400',
                    'methods' => [
                        [
                            'key'     => 'crypto',
                            'icon'    => '₿',
                            'name'    => 'Crypto — NowPayments',
                            'sub'     => 'Bitcoin • Ethereum • USDT +300',
                            'toggle'  => 'crypto_enabled',
                            'fields'  => [
                                ['key'=>'nowpayments_api_key',   'label'=>'API Key',       'type'=>'text',     'placeholder'=>'NP-xxxxxxxxxxxxxxxx'],
                                ['key'=>'nowpayments_public_key','label'=>'Public Key',    'type'=>'text',     'placeholder'=>'Public Key'],
                                ['key'=>'nowpayments_ipn_secret','label'=>'IPN Secret',    'type'=>'password'],
                                ['key'=>'crypto_webhook_secret', 'label'=>'Webhook Secret','type'=>'password'],
                            ],
                            'sandbox' => 'nowpayments_sandbox',
                            'info'    => 'Renseignez votre API Key NowPayments — les paiements crypto seront automatiquement confirmés.',
                        ],
                        [
                            'key'     => 'satim',
                            'icon'    => '💳',
                            'name'    => 'SATIM',
                            'sub'     => 'Visa • Mastercard • Edahabia',
                            'toggle'  => 'satim_enabled',
                            'fields'  => [
                                ['key'=>'satim_merchant_id','label'=>'Merchant ID', 'type'=>'text',    'placeholder'=>'Fourni par SATIM'],
                                ['key'=>'satim_terminal_id','label'=>'Terminal ID', 'type'=>'text',    'placeholder'=>'ID Terminal'],
                                ['key'=>'satim_api_key',    'label'=>'API Key',     'type'=>'password'],
                                ['key'=>'satim_secret_key', 'label'=>'Secret Key',  'type'=>'password'],
                                ['key'=>'satim_webhook',    'label'=>'Webhook Secret','type'=>'password'],
                            ],
                            'sandbox' => 'satim_sandbox',
                        ],
                        [
                            'key'     => 'paypal',
                            'icon'    => '🅿️',
                            'name'    => 'PayPal',
                            'sub'     => 'Paiement international sécurisé',
                            'toggle'  => 'paypal_enabled',
                            'fields'  => [
                                ['key'=>'paypal_client_id',     'label'=>'Client ID',     'type'=>'text'],
                                ['key'=>'paypal_client_secret', 'label'=>'Client Secret', 'type'=>'password'],
                            ],
                            'sandbox' => 'paypal_sandbox',
                        ],
                        [
                            'key'     => 'redotpay',
                            'icon'    => '🔴',
                            'name'    => 'RedotPay',
                            'sub'     => 'Crypto Card',
                            'toggle'  => null,
                            'fields'  => [
                                ['key'=>'redotpay_api_key','label'=>'API Key','type'=>'text','placeholder'=>'Votre API Key RedotPay'],
                            ],
                            'sandbox' => 'redotpay_sandbox',
                        ],
                    ],
                ],
                [
                    'title'   => 'Virement bancaire (manuel)',
                    'color'   => 'text-[#C9A24B]',
                    'methods' => [
                        [
                            'key'    => 'baridimob',
                            'icon'   => '📱',
                            'name'   => 'BaridiMob',
                            'sub'    => 'Algérie Poste — CCP',
                            'toggle' => 'baridimob_enabled',
                            'fields' => [
                                ['key'=>'baridimob_holder','label'=>'Titulaire', 'type'=>'text','placeholder'=>'Boutique Paris'],
                                ['key'=>'baridimob_rib',   'label'=>'N° CCP',   'type'=>'text','placeholder'=>'0012345678'],
                            ],
                        ],
                        [
                            'key'    => 'cib',
                            'icon'   => '💳',
                            'name'   => 'CIB / Edahabia',
                            'sub'    => 'Carte bancaire Algérie',
                            'toggle' => 'cib_enabled',
                            'fields' => [
                                ['key'=>'cib_holder','label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],
                                ['key'=>'cib_rib',   'label'=>'RIB',      'type'=>'text','placeholder'=>'00799999XXXXXXXXXX'],
                            ],
                        ],
                        [
                            'key'    => 'wise',
                            'icon'   => '🌍',
                            'name'   => 'Wise',
                            'sub'    => 'Virement international',
                            'toggle' => 'wise_enabled',
                            'fields' => [
                                ['key'=>'wise_holder','label'=>'Titulaire',  'type'=>'text', 'placeholder'=>'Boutique Paris'],
                                ['key'=>'wise_email', 'label'=>'Email Wise', 'type'=>'email','placeholder'=>'votre@wise.com'],
                            ],
                        ],
                        [
                            'key'    => 'mastercard',
                            'icon'   => '💳',
                            'name'   => 'Mastercard International',
                            'sub'    => 'Carte internationale',
                            'toggle' => 'mastercard_enabled',
                            'fields' => [
                                ['key'=>'mastercard_holder','label'=>'Titulaire',    'type'=>'text','placeholder'=>'Boutique Paris'],
                                ['key'=>'mastercard_rib',   'label'=>'IBAN / Compte','type'=>'text','placeholder'=>'DZ...'],
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Banques Algériennes (RIB)',
                    'color'   => 'text-blue-400',
                    'methods' => [
                        ['key'=>'bank_satim','icon'=>'💳','name'=>'SATIM (Virement)', 'sub'=>'Visa • Mastercard • Edahabia','toggle'=>'bank_satim_enabled','fields'=>[['key'=>'bank_satim_holder','label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],['key'=>'bank_satim_rib','label'=>'RIB','type'=>'text','placeholder'=>'00799999XXXXXXXXX']]],
                        ['key'=>'bank_cpa', 'icon'=>'🏛️','name'=>'CPA e-Payment',   'sub'=>"Crédit Populaire d'Algérie", 'toggle'=>'bank_cpa_enabled', 'fields'=>[['key'=>'bank_cpa_holder', 'label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],['key'=>'bank_cpa_rib', 'label'=>'RIB','type'=>'text','placeholder'=>'00799999XXXXXXXXX']]],
                        ['key'=>'bank_bdl', 'icon'=>'🏢','name'=>'BDL e-Payment',   'sub'=>'Banque de Développement Local','toggle'=>'bank_bdl_enabled', 'fields'=>[['key'=>'bank_bdl_holder', 'label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],['key'=>'bank_bdl_rib', 'label'=>'RIB','type'=>'text','placeholder'=>'00799999XXXXXXXXX']]],
                        ['key'=>'bank_badr','icon'=>'🌾','name'=>'BADR e-Payment',  'sub'=>"Banque de l'Agriculture",    'toggle'=>'bank_badr_enabled','fields'=>[['key'=>'bank_badr_holder','label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],['key'=>'bank_badr_rib','label'=>'RIB','type'=>'text','placeholder'=>'00799999XXXXXXXXX']]],
                        ['key'=>'bank_bna', 'icon'=>'🏦','name'=>'BNA e-Payment',   'sub'=>"Banque Nationale d'Algérie", 'toggle'=>'bank_bna_enabled', 'fields'=>[['key'=>'bank_bna_holder', 'label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],['key'=>'bank_bna_rib', 'label'=>'RIB','type'=>'text','placeholder'=>'00799999XXXXXXXXX']]],
                        ['key'=>'bank_agb', 'icon'=>'🌍','name'=>'AGB',             'sub'=>'Visa • Mastercard',          'toggle'=>'bank_agb_enabled', 'fields'=>[['key'=>'bank_agb_holder', 'label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],['key'=>'bank_agb_rib', 'label'=>'RIB','type'=>'text','placeholder'=>'00799999XXXXXXXXX']]],
                        ['key'=>'bank_sg',  'icon'=>'🔴','name'=>'Société Générale','sub'=>'SGA • CIB • EDAHABIA',       'toggle'=>'bank_sg_enabled',  'fields'=>[['key'=>'bank_sg_holder',  'label'=>'Titulaire','type'=>'text','placeholder'=>'Boutique Paris'],['key'=>'bank_sg_rib',  'label'=>'RIB','type'=>'text','placeholder'=>'00799999XXXXXXXXX']]],
                    ],
                ],
            ];
        @endphp

        @foreach ($sections as $si => $section)
            <div class="border border-white/8 rounded-xl overflow-hidden">
                <div class="px-5 py-3 bg-white/3 border-b border-white/8">
                    <p class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest {{ $section['color'] }}">
                        {{ $section['title'] }}
                    </p>
                </div>

                <div class="divide-y divide-white/5">
                    @foreach ($section['methods'] as $method)
                        @php
                            $uid       = $method['key'] . '_' . $si;
                            $isEnabled = $method['toggle']
                                ? \App\Models\Setting::get($method['toggle']) == '1'
                                : true;
                        @endphp

                        <div :class="open === '{{ $uid }}' ? 'bg-[#C9A24B]/3' : ''" class="transition-all">

                            <div class="flex items-center justify-between px-5 py-4 cursor-pointer"
                                 @click="open = (open === '{{ $uid }}' ? null : '{{ $uid }}')">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">{{ $method['icon'] }}</span>
                                    <div>
                                        <p class="font-[IBM_Plex_Mono] text-xs font-medium text-[#F6F3EC]">{{ $method['name'] }}</p>
                                        <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">{{ $method['sub'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-[IBM_Plex_Mono] text-[9px] px-2 py-0.5 rounded-full {{ $isEnabled ? 'text-green-400 bg-green-400/10 border border-green-400/20' : 'text-[#9C9788] bg-white/5' }}">
                                        {{ $isEnabled ? '✓ Actif' : 'Inactif' }}
                                    </span>
                                    <span class="text-[#9C9788] text-xs transition-transform duration-200"
                                          :class="open === '{{ $uid }}' ? 'rotate-180' : ''">▼</span>
                                </div>
                            </div>

                            <div x-show="open === '{{ $uid }}'" x-transition class="px-5 pb-5 pt-3 space-y-3">

                                @if ($method['toggle'])
                                    <label class="flex items-center gap-2 cursor-pointer p-3 bg-white/3 rounded-lg">
                                        <input type="checkbox" name="{{ $method['toggle'] }}" value="1"
                                               {{ $isEnabled ? 'checked' : '' }}
                                               class="accent-[#C9A24B] w-4 h-4">
                                        <span class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">
                                            Activer {{ $method['name'] }}
                                        </span>
                                    </label>
                                @endif

                                @if (!empty($method['info']))
                                    <div class="bg-blue-400/5 border border-blue-400/20 rounded-lg p-3">
                                        <p class="font-[IBM_Plex_Mono] text-[10px] text-blue-400">ℹ️ {{ $method['info'] }}</p>
                                    </div>
                                @endif

                                @foreach ($method['fields'] as $field)
                                    <div>
                                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-1.5">
                                            {{ $field['label'] }}
                                        </label>
                                        <input type="{{ $field['type'] }}"
                                               name="{{ $field['key'] }}"
                                               value="{{ $field['type'] !== 'password' ? \App\Models\Setting::get($field['key']) : '' }}"
                                               placeholder="{{ $field['placeholder'] ?? ($field['type'] === 'password' ? '••••••••' : '') }}"
                                               class="w-full bg-[#14151C] border border-white/15 px-4 py-2.5 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg transition-colors text-[#F6F3EC]">
                                    </div>
                                @endforeach

                                @if (!empty($method['sandbox']))
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="{{ $method['sandbox'] }}" value="1"
                                               {{ \App\Models\Setting::get($method['sandbox'], '1') == '1' ? 'checked' : '' }}
                                               class="accent-[#C9A24B] w-4 h-4">
                                        <span class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">Mode Test (Sandbox)</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="pt-2">
            <button type="submit"
                    class="px-8 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-xl hover:bg-[#dab564] transition-colors font-bold">
                Enregistrer tout
            </button>
        </div>
    </form>
@endsection