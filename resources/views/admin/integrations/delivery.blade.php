@extends('admin.layouts.app')
@section('title', 'Livraison')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.integrations.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">🚚 Livraison</h1>
    </div>

    <form action="{{ route('admin.integrations.delivery.save') }}" method="POST" class="space-y-4 max-w-2xl"
          x-data="{ open: null }">
        @csrf

        @php
            $carriers = [
                [
                    'key'   => 'yalidine',
                    'name'  => 'Yalidine',
                    'icon'  => '🟡',
                    'fields' => [
                        ['key' => 'yalidine_api_id',      'label' => 'API ID',      'type' => 'text'],
                        ['key' => 'yalidine_api_token',   'label' => 'API Token',   'type' => 'password'],
                        ['key' => 'yalidine_webhook_url', 'label' => 'Webhook URL', 'type' => 'url'],
                    ],
                    'extra' => [
                        [
                            'type'    => 'select',
                            'key'     => 'yalidine_mode',
                            'label'   => 'Mode',
                            'options' => ['sandbox' => 'Sandbox (test)', 'production' => 'Production'],
                        ],
                    ],
                ],
                [
                    'key'   => 'zr_express',
                    'name'  => 'ZR Express',
                    'icon'  => '🔵',
                    'fields' => [
                        ['key' => 'zr_express_key',     'label' => 'API Key',     'type' => 'text'],
                        ['key' => 'zr_express_secret',  'label' => 'Secret Key',  'type' => 'password'],
                        ['key' => 'zr_express_webhook', 'label' => 'Webhook URL', 'type' => 'url'],
                    ],
                ],
                [
                    'key'   => 'maystro',
                    'name'  => 'Maystro Delivery',
                    'icon'  => '🟢',
                    'fields' => [
                        ['key' => 'maystro_api_key', 'label' => 'API Key',     'type' => 'text'],
                        ['key' => 'maystro_token',   'label' => 'Token',       'type' => 'password'],
                        ['key' => 'maystro_webhook', 'label' => 'Webhook URL', 'type' => 'url'],
                    ],
                ],
                [
                    'key'   => 'ecotrack',
                    'name'  => 'Ecotrack',
                    'icon'  => '🟠',
                    'fields' => [
                        ['key' => 'ecotrack_username', 'label' => 'Username',    'type' => 'text'],
                        ['key' => 'ecotrack_password', 'label' => 'Password',    'type' => 'password'],
                        ['key' => 'ecotrack_api_key',  'label' => 'API Key',     'type' => 'password'],
                        ['key' => 'ecotrack_webhook',  'label' => 'Webhook URL', 'type' => 'url'],
                    ],
                ],
            ];
        @endphp

        @foreach ($carriers as $carrier)
            @php $isEnabled = \App\Models\Setting::get($carrier['key'] . '_enabled') == '1'; @endphp

            <div class="border rounded-xl overflow-hidden transition-all duration-200"
                 :class="open === '{{ $carrier['key'] }}' ? 'border-[#C9A24B]/40 bg-[#C9A24B]/3' : 'border-white/10 hover:border-white/25'">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 cursor-pointer"
                     @click="open = (open === '{{ $carrier['key'] }}' ? null : '{{ $carrier['key'] }}')">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">{{ $carrier['icon'] }}</span>
                        <div>
                            <p class="font-[Fraunces] text-base">{{ $carrier['name'] }}</p>
                            @if ($isEnabled)
                                <p class="font-[IBM_Plex_Mono] text-[9px] text-green-400">✓ Activé</p>
                            @else
                                <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">Désactivé</p>
                            @endif
                        </div>
                    </div>
                    <span class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]"
                          :class="open === '{{ $carrier['key'] }}' ? 'rotate-180' : ''"
                          style="transition: transform 0.2s">▼</span>
                </div>

                {{-- Body --}}
                <div x-show="open === '{{ $carrier['key'] }}'" x-transition class="border-t border-white/10 px-5 pb-5 pt-4 space-y-4">

                    {{-- Toggle Activer --}}
                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-white/3 rounded-lg">
                        <input type="checkbox" name="{{ $carrier['key'] }}_enabled" value="1"
                               {{ $isEnabled ? 'checked' : '' }}
                               class="accent-[#C9A24B] w-4 h-4">
                        <span class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">Activer {{ $carrier['name'] }}</span>
                    </label>

                    {{-- Fields --}}
                    @foreach ($carrier['fields'] as $field)
                        <div>
                            <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-1.5">
                                {{ $field['label'] }}
                            </label>
                            <input type="{{ $field['type'] }}"
                                   name="{{ $field['key'] }}"
                                   value="{{ $field['type'] !== 'password' ? \App\Models\Setting::get($field['key']) : '' }}"
                                   placeholder="{{ $field['type'] === 'password' ? '••••••••' : ($field['type'] === 'url' ? 'https://...' : '') }}"
                                   class="w-full bg-transparent border border-white/20 px-4 py-2.5 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg transition-colors">
                        </div>
                    @endforeach

                    {{-- Extra (select) --}}
                    @if (!empty($carrier['extra']))
                        @foreach ($carrier['extra'] as $extra)
                            @if ($extra['type'] === 'select')
                                <div>
                                    <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-1.5">
                                        {{ $extra['label'] }}
                                    </label>
                                    <select name="{{ $extra['key'] }}"
                                            class="w-full bg-[#14151C] border border-white/20 px-4 py-2.5 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC]">
                                        @foreach ($extra['options'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ \App\Models\Setting::get($extra['key'], 'sandbox') === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach

        <div class="pt-2">
            <button type="submit"
                    class="px-6 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors">
                Enregistrer tout
            </button>
        </div>
    </form>
@endsection