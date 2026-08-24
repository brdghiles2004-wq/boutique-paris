<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin — @yield('title', 'Boutique Paris')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            font-size: 15px;
        }

        .sidebar {
            transition: width 0.3s ease, transform 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .footer-text {
            display: none;
        }

        .main-content {
            transition: margin-left 0.3s ease;
        }

        /* =========================
           MOBILE
        ========================= */
        @media (max-width: 767px) {

            .sidebar {
                width: 270px !important;
                transform: translateX(-100%);
                box-shadow: 20px 0 50px rgba(0,0,0,.45);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 270px !important;
            }

            .sidebar.collapsed .nav-label,
            .sidebar.collapsed .logo-text,
            .sidebar.collapsed .footer-text {
                display: inline;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }

            .admin-content {
                padding: 16px !important;
            }

            .topbar {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .desktop-sidebar-button {
                display: none !important;
            }

            .mobile-menu-button {
                display: flex !important;
            }

            .mobile-overlay {
                display: none;
            }

            .mobile-overlay.active {
                display: block;
            }
        }

        @media (min-width: 768px) {
            .mobile-menu-button {
                display: none !important;
            }

            .mobile-overlay {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-[#14151C] text-[#F6F3EC] font-[Inter] antialiased">

@php
    $unreadNotifs  = auth()->user()->unreadNotifications->count();
    $unreadSupport = \App\Models\SupportMessage::where('is_read', false)->count();

    $pendingOrders = \App\Models\Order::where('status', 'pending')->count();

    $lowStockCount = \App\Models\Product::whereHas(
        'variants',
        fn($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5)
    )->count();

    $outOfStock = \App\Models\Product::where('is_active', true)
        ->whereDoesntHave(
            'variants',
            fn($q) => $q->where('stock', '>', 0)
        )
        ->count();
@endphp


<div class="flex min-h-screen w-full">


    {{-- =========================================
         MOBILE OVERLAY
    ========================================== --}}
    <div id="mobile-overlay"
         class="mobile-overlay fixed inset-0 bg-black/60 backdrop-blur-[2px] z-30"
         onclick="closeMobileSidebar()">
    </div>


    {{-- =========================================
         SIDEBAR
    ========================================== --}}
    <aside id="sidebar"
           class="sidebar w-64 bg-[#1C1E27] border-r border-white/10 flex flex-col fixed inset-y-0 left-0 z-40 overflow-hidden">


        {{-- LOGO --}}
        <div class="flex items-center justify-between px-5 py-5 border-b border-white/10">

            <a href="{{ route('admin.dashboard') }}"
               class="logo-text flex items-center gap-2">

                <div class="w-8 h-8 rounded-lg bg-[#C9A24B] flex items-center justify-center flex-shrink-0">
                    <span class="font-[Fraunces] text-[#14151C] text-sm font-bold italic">
                        B
                    </span>
                </div>

                <div>
                    <span class="font-[Fraunces] text-base">
                        Boutique
                        <span class="text-[#C9A24B] italic">Paris</span>
                    </span>

                    <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] uppercase tracking-widest">
                        Admin Panel
                    </p>
                </div>
            </a>


            {{-- DESKTOP COLLAPSE --}}
            <button id="toggle-sidebar"
                    class="desktop-sidebar-button w-7 h-7 items-center justify-center rounded-lg border border-white/10 text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all text-xs flex-shrink-0">
                ◀
            </button>


            {{-- MOBILE CLOSE --}}
            <button onclick="closeMobileSidebar()"
                    class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg border border-white/10 text-[#9C9788]">
                ✕
            </button>

        </div>


        {{-- ALERTS --}}
        <div class="nav-label px-3 pt-3 space-y-2">

            @if ($pendingOrders > 0)
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg bg-yellow-400/10 border border-yellow-400/20">

                    <span class="text-sm">📦</span>

                    <span class="font-[IBM_Plex_Mono] text-[12px] text-yellow-400">
                        {{ $pendingOrders }} commande(s) en attente
                    </span>
                </a>
            @endif


            @if ($lowStockCount > 0)
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg bg-orange-400/10 border border-orange-400/20">

                    <span class="text-sm">⚠️</span>

                    <span class="font-[IBM_Plex_Mono] text-[12px] text-orange-400">
                        {{ $lowStockCount }} stock(s) faible(s)
                    </span>
                </a>
            @endif


            @if ($outOfStock > 0)
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg bg-red-400/10 border border-red-400/20">

                    <span class="text-sm">🚫</span>

                    <span class="font-[IBM_Plex_Mono] text-[10px] text-red-400">
                        {{ $outOfStock }} rupture(s)
                    </span>
                </a>
            @endif

        </div>


        {{-- NAVIGATION --}}
        <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">

            @php
                $navItems = [
                    [
                        'route' => 'admin.dashboard',
                        'label' => 'Dashboard',
                        'icon' => '📊'
                    ],
                    [
                        'route' => 'admin.analytics',
                        'label' => 'Analytics',
                        'icon' => '📈'
                    ],
                    [
                        'route' => 'admin.products.index',
                        'label' => 'Produits',
                        'icon' => '👕'
                    ],
                    [
                        'route' => 'admin.categories.index',
                        'label' => 'Catégories',
                        'icon' => '📁'
                    ],
                    [
                        'route' => 'admin.orders.index',
                        'label' => 'Commandes',
                        'icon' => '📦',
                        'badge' => $pendingOrders
                    ],
                    [
                        'route' => 'admin.users.index',
                        'label' => 'Clients',
                        'icon' => '👥'
                    ],
                    [
                        'route' => 'admin.support.index',
                        'label' => 'Support',
                        'icon' => '💬',
                        'badge' => $unreadSupport
                    ],
                    [
                        'route' => 'admin.integrations.index',
                        'label' => 'Intégrations',
                        'icon' => '⚙️'
                    ],
                ];
            @endphp


            @foreach ($navItems as $item)

                @php
                    $isActive = request()->routeIs(
                        rtrim($item['route'], '.index') . '*'
                    );
                @endphp

                <a href="{{ route($item['route']) }}"
                   onclick="closeMobileSidebar()"
                   class="flex items-center justify-between px-3 py-3 rounded-xl transition-all
                    {{ $isActive
                        ? 'bg-[#C9A24B]/12 text-[#C9A24B] border border-[#C9A24B]/20'
                        : 'text-[#9C9788] hover:text-[#F6F3EC] hover:bg-white/5 border border-transparent'
                    }}">

                    <div class="flex items-center gap-3 min-w-0">

                        <span class="text-lg flex-shrink-0">
                            {{ $item['icon'] }}
                        </span>

                        <span class="nav-label font-[IBM_Plex_Mono] text-xs tracking-wide truncate">
                            {{ $item['label'] }}
                        </span>

                    </div>


                    @if (!empty($item['badge']) && $item['badge'] > 0)

                        <span class="nav-label bg-[#C9A24B] text-[#14151C] text-[9px] font-bold rounded-full min-w-[20px] h-[20px] flex items-center justify-center px-1 flex-shrink-0">
                            {{ $item['badge'] }}
                        </span>

                    @endif

                </a>

            @endforeach

        </nav>


        {{-- FOOTER --}}
        <div class="px-3 pb-4 pt-3 border-t border-white/10 space-y-1">

            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[#9C9788] hover:text-[#C9A24B] hover:bg-white/5 transition-all">

                <span class="text-base">🌐</span>

                <span class="nav-label footer-text font-[IBM_Plex_Mono] text-xs">
                    Voir le site
                </span>

            </a>


            <form action="{{ route('logout') }}" method="POST">

                @csrf

                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[#9C9788] hover:text-[#e9b3bb] hover:bg-[#7A2E3A]/10 transition-all">

                    <span class="text-base">🚪</span>

                    <span class="nav-label footer-text font-[IBM_Plex_Mono] text-xs">
                        Déconnexion
                    </span>

                </button>

            </form>

        </div>

    </aside>



    {{-- =========================================
         MAIN
    ========================================== --}}
    <main id="main-content"
          class="main-content ml-64 flex-1 min-h-screen min-w-0">


        {{-- TOP BAR --}}
        <div class="topbar sticky top-0 z-20 bg-[#14151C]/95 backdrop-blur border-b border-white/10 px-8 py-4 flex items-center justify-between">


            {{-- MOBILE MENU --}}
            <div class="flex items-center gap-3">

                <button id="mobile-menu-button"
                        onclick="openMobileSidebar()"
                        class="mobile-menu-button hidden w-9 h-9 items-center justify-center rounded-lg border border-white/10 text-[#F6F3EC] text-lg">

                    ☰

                </button>


                {{-- BREADCRUMB --}}
                <div class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">

                    <a href="{{ route('admin.dashboard') }}"
                       class="hover:text-[#C9A24B]">

                        Dashboard

                    </a>

                    <span class="mx-1">›</span>

                    <span class="text-[#F6F3EC]">
                        @yield('title', 'Dashboard')
                    </span>

                </div>

            </div>


            {{-- USER --}}
            <div class="flex items-center gap-3">

                @if ($unreadNotifs > 0)

                    <a href="{{ route('admin.notifications.index') }}"
                       class="relative">

                        <span class="text-lg">
                            🔔
                        </span>

                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] font-bold rounded-full w-4 h-4 flex items-center justify-center">

                            {{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}

                        </span>

                    </a>

                @endif


                <span class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] hidden sm:block">

                    {{ auth()->user()->name }}

                </span>

            </div>

        </div>



        {{-- ALERTS --}}
        @if (session('success'))

            <div class="mx-8 mt-6 bg-[#C9A24B]/10 border border-[#C9A24B]/30 text-[#C9A24B] font-[IBM_Plex_Mono] text-xs px-5 py-3 rounded-xl">

                ✓ {{ session('success') }}

            </div>

        @endif


        @if (session('error'))

            <div class="mx-8 mt-6 bg-red-500/10 border border-red-500/20 text-red-400 font-[IBM_Plex_Mono] text-xs px-5 py-3 rounded-xl">

                ✕ {{ session('error') }}

            </div>

        @endif



        {{-- CONTENT --}}
        <div class="admin-content p-8">

            @yield('content')

        </div>

    </main>

</div>



<script>

    const sidebar = document.getElementById('sidebar');
    const main    = document.getElementById('main-content');
    const btn     = document.getElementById('toggle-sidebar');
    const overlay = document.getElementById('mobile-overlay');

    let collapsed = localStorage.getItem('sidebar-collapsed') === 'true';


    /* ==========================
       DESKTOP SIDEBAR
    ========================== */

    function applyDesktopSidebar() {

        if (window.innerWidth < 768) {

            main.style.marginLeft = '0px';

            return;
        }


        if (collapsed) {

            sidebar.classList.add('collapsed');

            sidebar.style.width = '70px';

            main.style.marginLeft = '70px';

            btn.textContent = '▶';

        } else {

            sidebar.classList.remove('collapsed');

            sidebar.style.width = '256px';

            main.style.marginLeft = '256px';

            btn.textContent = '◀';

        }

    }


    /* ==========================
       MOBILE SIDEBAR
    ========================== */

    function openMobileSidebar() {

        sidebar.classList.add('mobile-open');

        overlay.classList.add('active');

        document.body.style.overflow = 'hidden';

    }


    function closeMobileSidebar() {

        sidebar.classList.remove('mobile-open');

        overlay.classList.remove('active');

        document.body.style.overflow = '';

    }


    /* ==========================
       DESKTOP BUTTON
    ========================== */

    btn.addEventListener('click', () => {

        if (window.innerWidth < 768) {
            return;
        }

        collapsed = !collapsed;

        localStorage.setItem(
            'sidebar-collapsed',
            collapsed
        );

        applyDesktopSidebar();

    });


    /* ==========================
       RESPONSIVE
    ========================== */

    window.addEventListener('resize', () => {

        applyDesktopSidebar();

        if (window.innerWidth >= 768) {
            closeMobileSidebar();
        }

    });


    applyDesktopSidebar();

</script>

</body>
</html>