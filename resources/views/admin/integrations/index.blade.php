@extends('admin.layouts.app')
@section('title', 'Intégrations')

@section('content')

    <div class="mb-8">
        <h1 class="font-[Fraunces] text-3xl">Intégrations</h1>
        <p class="font-[IBM_Plex_Mono] text-[15px] text-[#9C9788] mt-1">Connectez votre boutique aux services externes</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @php
            $sections = [
                [
                    'title' => 'Livraison',
                    'icon' => '🚚',
                    'route' => 'admin.integrations.delivery',
                    'color' => '#6366f1',
                    'bg' => 'rgba(99,102,241,0.08)',
                    'border' => 'rgba(99,102,241,0.2)',
                    'items' => ['Yalidine', 'ZR Express', 'Maystro', 'Ecotrack'],
                ],
                [
                    'title' => 'Paiement',
                    'icon' => '💳',
                    'route' => 'admin.integrations.payment',
                    'color' => '#22c55e',
                    'bg' => 'rgba(34,197,94,0.08)',
                    'border' => 'rgba(34,197,94,0.2)',
                    'items' => ['PayPal', 'BaridiMob', 'Carte Bancaire', 'Wise', 'Crypto'],
                ],
                [
                    'title' => 'Marketing',
                    'icon' => '📈',
                    'route' => 'admin.integrations.marketing',
                    'color' => '#C9A24B',
                    'bg' => 'rgba(201,162,75,0.08)',
                    'border' => 'rgba(201,162,75,0.2)',
                    'items' => ['Facebook Pixel', 'Meta CAPI', 'Google Analytics', 'GTM', 'TikTok Pixel'],
                ],
                [
                    'title' => 'Email',
                    'icon' => '📧',
                    'route' => 'admin.integrations.email',
                    'color' => '#3b82f6',
                    'bg' => 'rgba(59,130,246,0.08)',
                    'border' => 'rgba(59,130,246,0.2)',
                    'items' => ['SMTP Custom', 'Resend', 'Templates'],
                ],
                [
                    'title' => 'Communication',
                    'icon' => '📱',
                    'route' => 'admin.integrations.communication',
                    'color' => '#10b981',
                    'bg' => 'rgba(16,185,129,0.08)',
                    'border' => 'rgba(16,185,129,0.2)',
                    'items' => ['WhatsApp Business'],
                ],
                [
                    'title' => 'SEO',
                    'icon' => '🔍',
                    'route' => 'admin.integrations.seo',
                    'color' => '#f97316',
                    'bg' => 'rgba(249,115,22,0.08)',
                    'border' => 'rgba(249,115,22,0.2)',
                    'items' => ['Google Search Console', 'Sitemap XML', 'Meta Tags'],
                ],
            ];
        @endphp

        @foreach ($sections as $section)
            <a href="{{ route($section['route']) }}"
               class="rounded-xl border p-6 hover:scale-[1.01] transition-all group"
               style="background: {{ $section['bg'] }}; border-color: {{ $section['border'] }}">

                <div class="flex items-start justify-between mb-5">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl"
                         style="background: {{ $section['bg'] }}; border: 1px solid {{ $section['border'] }}">
                        {{ $section['icon'] }}
                    </div>
                    <span class="font-[IBM_Plex_Mono] text-[13px] uppercase tracking-widest group-hover:translate-x-1 transition-transform"
                          style="color: {{ $section['color'] }}">
                        Configurer →
                    </span>
                </div>

                <h2 class="font-[Fraunces] text-xl mb-3" style="color: {{ $section['color'] }}">
                    {{ $section['title'] }}
                </h2>

                <div class="flex flex-wrap gap-2">
                    @foreach ($section['items'] as $item)
                        <span class="font-[IBM_Plex_Mono] text-[12px] px-2 py-0.5 rounded bg-white/5 text-[#9C9788]">
                            {{ $item }}
                        </span>
                    @endforeach
                </div>
            </a>
            @endforeach

        {{-- ===== OAUTH ===== --}}
        <a href="{{ route('admin.integrations.oauth') }}"
           class="xl:col-span-3 bg-[#1C1E27] border border-white/10 rounded-2xl p-6 hover:border-[#C9A24B]/30 transition-all group block">

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-400/10 border border-blue-400/20 flex items-center justify-center text-2xl">
                    🔐
                </div>

                <div>
                    <h3 class="font-[Fraunces] text-lg group-hover:text-[#C9A24B] transition-colors">
                        OAuth & Authentification
                    </h3>

                    <p class="font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">
                        Google Login • App URL
                    </p>
                </div>
            </div>

        </a>

    </div>

@endsection