@extends('admin.layouts.app')
@section('title', 'Marketing')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.integrations.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">📈 Marketing</h1>
    </div>

    <form action="{{ route('admin.integrations.marketing.save') }}" method="POST"
          class="space-y-4 max-w-2xl" x-data="{ open: null }">
        @csrf

        @php
            $groups = [
                [
                    'group' => 'Meta (Facebook / Instagram)',
                    'icon'  => '📘',
                    'key'   => 'meta',
                    'color' => 'text-blue-400',
                    'tools' => [
                        [
                            'key'    => 'fb_pixel',
                            'name'   => 'Facebook Pixel',
                            'icon'   => '📘',
                            'fields' => [
                                ['key' => 'fb_pixel_id', 'label' => 'Pixel ID', 'type' => 'text', 'placeholder' => '123456789012345'],
                            ],
                            'toggle' => 'fb_pixel_enabled',
                        ],
                        [
                            'key'    => 'meta_capi',
                            'name'   => 'Meta Conversion API',
                            'icon'   => '🔗',
                            'fields' => [
                                ['key' => 'meta_access_token', 'label' => 'Access Token', 'type' => 'password'],
                                ['key' => 'meta_page_id',      'label' => 'Page ID',      'type' => 'text', 'placeholder' => 'Votre Page ID Facebook'],
                            ],
                            'toggle' => 'meta_capi_enabled',
                        ],
                    ],
                ],
                [
                    'group' => 'Google',
                    'icon'  => '📊',
                    'key'   => 'google',
                    'color' => 'text-red-400',
                    'tools' => [
                        [
                            'key'    => 'ga',
                            'name'   => 'Google Analytics 4',
                            'icon'   => '📊',
                            'fields' => [
                                ['key' => 'ga_measurement_id', 'label' => 'Measurement ID', 'type' => 'text', 'placeholder' => 'G-XXXXXXXXXX'],
                            ],
                            'toggle' => 'ga_enabled',
                        ],
                        [
                            'key'    => 'gtm',
                            'name'   => 'Google Tag Manager',
                            'icon'   => '🏷️',
                            'fields' => [
                                ['key' => 'gtm_id', 'label' => 'Container ID', 'type' => 'text', 'placeholder' => 'GTM-XXXXXXX'],
                            ],
                            'toggle' => 'gtm_enabled',
                        ],
                        [
                            'key'    => 'google_ads',
                            'name'   => 'Google Ads',
                            'icon'   => '🎯',
                            'fields' => [
                                ['key' => 'google_ads_id',    'label' => 'Conversion ID',    'type' => 'text', 'placeholder' => 'AW-XXXXXXXXX'],
                                ['key' => 'google_ads_label', 'label' => 'Conversion Label', 'type' => 'text', 'placeholder' => 'Label de conversion'],
                            ],
                            'toggle' => 'google_ads_enabled',
                        ],
                    ],
                ],
                [
                    'group' => 'TikTok',
                    'icon'  => '🎵',
                    'key'   => 'tiktok',
                    'color' => 'text-pink-400',
                    'tools' => [
                        [
                            'key'    => 'tiktok_pixel',
                            'name'   => 'TikTok Pixel',
                            'icon'   => '🎵',
                            'fields' => [
                                ['key' => 'tiktok_pixel_id',     'label' => 'Pixel ID',      'type' => 'text'],
                                ['key' => 'tiktok_access_token', 'label' => 'Access Token',  'type' => 'password'],
                            ],
                            'toggle' => 'tiktok_pixel_enabled',
                        ],
                    ],
                ],
            ];
        @endphp

        @foreach ($groups as $group)
            {{-- Group Header --}}
            <div class="border border-white/8 rounded-xl overflow-hidden">
                <div class="px-5 py-3 bg-white/3 border-b border-white/8 flex items-center gap-2">
                    <span>{{ $group['icon'] }}</span>
                    <p class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest {{ $group['color'] }}">
                        {{ $group['group'] }}
                    </p>
                </div>

                <div class="divide-y divide-white/5">
                    @foreach ($group['tools'] as $tool)
                        @php $isEnabled = \App\Models\Setting::get($tool['toggle']) == '1'; @endphp

                        <div class="transition-all duration-200"
                             :class="open === '{{ $tool['key'] }}' ? 'bg-[#C9A24B]/3' : ''">

                            {{-- Tool Header --}}
                            <div class="flex items-center justify-between px-5 py-3.5 cursor-pointer"
                                 @click="open = (open === '{{ $tool['key'] }}' ? null : '{{ $tool['key'] }}')">
                                <div class="flex items-center gap-3">
                                    <span class="text-base">{{ $tool['icon'] }}</span>
                                    <div>
                                        <p class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">{{ $tool['name'] }}</p>
                                        @if ($isEnabled)
                                            <p class="font-[IBM_Plex_Mono] text-[9px] text-green-400">✓ Actif</p>
                                        @else
                                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">Inactif</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] transition-transform duration-200"
                                      :class="open === '{{ $tool['key'] }}' ? 'rotate-180' : ''">▼</span>
                            </div>

                            {{-- Tool Fields --}}
                            <div x-show="open === '{{ $tool['key'] }}'" x-transition
                                 class="px-5 pb-4 pt-2 space-y-3">

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="{{ $tool['toggle'] }}" value="1"
                                           {{ $isEnabled ? 'checked' : '' }}
                                           class="accent-[#C9A24B] w-4 h-4">
                                    <span class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">
                                        Activer {{ $tool['name'] }}
                                    </span>
                                </label>

                                @foreach ($tool['fields'] as $field)
                                    <div>
                                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-1.5">
                                            {{ $field['label'] }}
                                        </label>
                                        <input type="{{ $field['type'] }}"
                                               name="{{ $field['key'] }}"
                                               value="{{ $field['type'] !== 'password' ? \App\Models\Setting::get($field['key']) : '' }}"
                                               placeholder="{{ $field['placeholder'] ?? ($field['type'] === 'password' ? '••••••••' : '') }}"
                                               class="w-full bg-transparent border border-white/20 px-4 py-2.5 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg transition-colors">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="bg-[#C9A24B]/5 border border-[#C9A24B]/20 rounded-xl p-4">
            <p class="font-[IBM_Plex_Mono] text-[11px] text-[#C9A24B]">
                ⚠️ Les pixels activés seront injectés automatiquement dans toutes les pages du site.
            </p>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="px-6 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors">
                Enregistrer tout
            </button>
        </div>
    </form>
@endsection