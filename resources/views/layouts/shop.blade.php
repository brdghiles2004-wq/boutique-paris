<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Boutique Paris')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#14151C">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $fbPixelId  = \App\Models\Setting::get('fb_pixel_id');
        $fbEnabled  = \App\Models\Setting::get('fb_pixel_enabled') == '1';
        $gaId       = \App\Models\Setting::get('ga_measurement_id');
        $gaEnabled  = \App\Models\Setting::get('ga_enabled') == '1';
        $gtmId      = \App\Models\Setting::get('gtm_id');
        $gtmEnabled = \App\Models\Setting::get('gtm_enabled') == '1';
        $ttPixelId  = \App\Models\Setting::get('tiktok_pixel_id');
        $ttEnabled  = \App\Models\Setting::get('tiktok_pixel_enabled') == '1';
        $gscCode    = \App\Models\Setting::get('gsc_verification');
    @endphp

    @if ($gscCode)
        <meta name="google-site-verification" content="{{ $gscCode }}">
    @endif

    @if ($gaEnabled && $gaId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif

    @if ($gtmEnabled && $gtmId)
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    @if ($fbEnabled && $fbPixelId)
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $fbPixelId }}');
            fbq('track', 'PageView');
        </script>
    @endif

    @if ($ttEnabled && $ttPixelId)
        <script>
            !function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e};ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{};ttq._i[e]=[];ttq._i[e]._u=i;ttq._t=ttq._t||{};ttq._t[e]=+new Date;ttq._u=ttq._u||{};n&&(ttq._u[e]=n);var o=d.createElement("script");o.type="text/javascript";o.async=!0;o.src=i+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};ttq.load('{{ $ttPixelId }}');ttq.page();}(window,document,'ttq');
        </script>
    @endif
</head>

<body class="bg-[#14151C] text-[#F6F3EC] font-[Inter] antialiased">

@if ($gtmEnabled ?? false && $gtmId ?? false)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

@php
    $navCategories = \App\Models\Category::main()->orderBy('order')->get();
    $currentCart   = auth()->check()
        ? \App\Models\Cart::where('user_id', auth()->id())->first()
        : \App\Models\Cart::where('session_id', session()->getId())->first();
    $cartCount = $currentCart ? $currentCart->items->sum('quantity') : 0;
    $locale    = app()->getLocale();
@endphp

<header x-data="{ open: false }"
        class="border-b border-white/10 sticky top-0 z-50 bg-[#14151C]/95 backdrop-blur">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:#14151C;border:1.5px solid #C9A24B;">
                <span class="font-[Fraunces] italic text-[#C9A24B] text-base font-bold">B</span>
            </div>
            <span class="font-[Fraunces] text-xl">
                Boutique <span class="text-[#C9A24B] italic">Paris</span>
            </span>
        </a>

        {{-- Nav catégories --}}
        <nav class="hidden md:flex items-center gap-8 font-[IBM_Plex_Mono] text-xs uppercase tracking-widest text-[#9C9788]">
            @foreach ($navCategories as $cat)
                <a href="{{ route('shop.category', $cat) }}" class="hover:text-[#C9A24B] transition-colors">
                    {{ $cat->name }}
                </a>
            @endforeach
        </nav>

        {{-- Actions --}}
        <div class="hidden md:flex items-center gap-3 font-[IBM_Plex_Mono] text-xs">

            <a href="{{ route('cart.index') }}" class="hover:text-[#C9A24B] transition-colors">
                {{ __('site.cart') }} ({{ $cartCount }})
            </a>

            <span class="text-[#9C9788]">|</span>

            <a href="{{ route('support') }}" class="hover:text-[#C9A24B] transition-colors">
                {{ __('site.support') }}
            </a>

            <span class="text-[#9C9788]">|</span>

            @auth
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="text-[#C9A24B] hover:underline">
                        ⚙ {{ __('site.admin') }}
                    </a>
                    <span class="text-[#9C9788]">|</span>
                @endif

                <a href="{{ route('profile') }}" class="hover:text-[#C9A24B] transition-colors">
                    {{ __('site.profile') }}
                </a>

                <span class="text-[#9C9788]">|</span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-[#C9A24B] transition-colors">
                        {{ __('site.logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-[#C9A24B] transition-colors">
                    {{ __('site.login') }}
                </a>
                <span class="text-[#9C9788]">/</span>
                <a href="{{ route('register') }}" class="hover:text-[#C9A24B] transition-colors">
                    {{ __('site.register') }}
                </a>
            @endauth

            {{-- Language Switcher --}}
            <div class="flex items-center gap-0.5 ml-1">
                @foreach (['fr' => '🇫🇷', 'en' => '🇬🇧', 'ar' => '🇩🇿'] as $lang => $flag)
                    <a href="{{ route('lang.switch', $lang) }}"
                       title="{{ strtoupper($lang) }}"
                       class="px-1.5 py-0.5 rounded text-[10px] transition-colors
                              {{ $locale === $lang
                                  ? 'text-[#C9A24B] border border-[#C9A24B]/40 bg-[#C9A24B]/10'
                                  : 'text-[#9C9788] hover:text-[#F6F3EC]' }}">
                        {{ $flag }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Mobile burger --}}
        <button @click="open = !open" class="md:hidden text-2xl">☰</button>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition class="md:hidden border-t border-white/10">
        <div class="flex flex-col p-5 gap-4 font-[IBM_Plex_Mono] text-xs">
            @foreach ($navCategories as $cat)
                <a href="{{ route('shop.category', $cat) }}" class="hover:text-[#C9A24B]">{{ $cat->name }}</a>
            @endforeach
            <hr class="border-white/10">
            <a href="{{ route('support') }}" class="hover:text-[#C9A24B]">{{ __('site.support') }}</a>
            <a href="{{ route('cart.index') }}" class="hover:text-[#C9A24B]">{{ __('site.cart') }} ({{ $cartCount }})</a>
            @auth
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="text-[#C9A24B]">⚙ Admin</a>
                @endif
                <a href="{{ route('profile') }}" class="hover:text-[#C9A24B]">{{ __('site.profile') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-[#C9A24B]">{{ __('site.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-[#C9A24B]">{{ __('site.login') }}</a>
                <a href="{{ route('register') }}" class="hover:text-[#C9A24B]">{{ __('site.register') }}</a>
            @endauth
            <hr class="border-white/10">
            <div class="flex items-center gap-2">
                @foreach (['fr' => '🇫🇷', 'en' => '🇬🇧', 'ar' => '🇩🇿'] as $lang => $flag)
                    <a href="{{ route('lang.switch', $lang) }}"
                       class="px-2 py-1 rounded text-[11px] transition-colors
                              {{ $locale === $lang ? 'text-[#C9A24B] border border-[#C9A24B]/40' : 'text-[#9C9788]' }}">
                        {{ $flag }} {{ strtoupper($lang) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>

{{-- Flash messages --}}
@if (session('success'))
    <div class="bg-[#C9A24B]/10 border-b border-[#C9A24B]/30 text-[#C9A24B] text-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 font-[IBM_Plex_Mono]">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="bg-[#7A2E3A]/20 border-b border-[#7A2E3A]/40 text-[#e9b3bb] text-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 font-[IBM_Plex_Mono]">{{ session('error') }}</div>
    </div>
@endif

<main>
    @yield('content')
</main>

<footer class="border-t border-white/10 mt-24">
    <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row justify-between gap-4 font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
        <div>
            <p>© {{ date('Y') }} Boutique Paris — Tous droits réservés.</p>
            <p class="mt-1 text-[#9C9788]/60">RC N° 16/00-0012345 — NIF 000016001234567</p>
        </div>
        <div class="flex gap-6">
            <a href="{{ route('support') }}" class="hover:text-[#C9A24B] transition-colors">Support</a>
            <span>Homme · Femme · Bébé · Enfants</span>
        </div>
    </div>
</footer>

{{-- WhatsApp --}}
@php
    $waNumber  = \App\Models\Setting::get('whatsapp_number');
    $waEnabled = \App\Models\Setting::get('whatsapp_enabled') == '1';
    $waMessage = urlencode(\App\Models\Setting::get('whatsapp_message', "Bonjour, j'ai une question..."));
@endphp

@if ($waEnabled && $waNumber)
    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
       target="_blank"
       class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform"
       style="background:#25D366"
       title="WhatsApp">
        <svg viewBox="0 0 24 24" fill="white" class="w-7 h-7">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
@endif

</body>
</html>