@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-[Fraunces] text-3xl">Dashboard</h1>

            <p class="font-[IBM_Plex_Mono] text-[14px] text-[#9C9788] mt-1">
                {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </p>
        </div>

        <a href="{{ route('admin.analytics') }}"
           class="font-[IBM_Plex_Mono] text-[12px] text-[#C9A24B] hover:underline uppercase tracking-wider">
            Analytics détaillés →
        </a>
    </div>


    {{-- ========================================================= --}}
    {{-- ===== STATS PRINCIPALES ===== --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

        @php
            $mainCards = [
                [
                    'label' => "Chiffre d'affaires",
                    'value' => number_format($stats['total_revenue'], 0, ',', ' ') . ' DA',
                    'sub' => $stats['paid_orders'] . ' commandes payées',
                    'icon' => '💰',
                    'color' => '#C9A24B',
                    'bg' => 'rgba(201,162,75,0.08)',
                    'border' => 'rgba(201,162,75,0.2)',
                    'link' => route('admin.orders.index', ['status' => 'paid']),
                ],
                [
                    'label' => 'Commandes',
                    'value' => $stats['total_orders'],
                    'sub' => $stats['pending_orders'] . ' en attente',
                    'icon' => '📦',
                    'color' => '#6366f1',
                    'bg' => 'rgba(99,102,241,0.08)',
                    'border' => 'rgba(99,102,241,0.2)',
                    'link' => route('admin.orders.index'),
                ],
                [
                    'label' => 'Clients',
                    'value' => $stats['total_clients'],
                    'sub' => '+' . $stats['new_clients_today'] . " aujourd'hui",
                    'icon' => '👥',
                    'color' => '#22c55e',
                    'bg' => 'rgba(34,197,94,0.08)',
                    'border' => 'rgba(34,197,94,0.2)',
                    'link' => route('admin.users.index'),
                ],
                [
                    'label' => 'Produits',
                    'value' => $stats['total_products'],
                    'sub' => $stats['out_of_stock'] . ' rupture de stock',
                    'icon' => '👕',
                    'color' => '#3b82f6',
                    'bg' => 'rgba(59,130,246,0.08)',
                    'border' => 'rgba(59,130,246,0.2)',
                    'link' => route('admin.products.index'),
                ],
            ];
        @endphp


        @foreach ($mainCards as $card)

            <a href="{{ $card['link'] }}"
               class="rounded-2xl p-6 border hover:scale-[1.01] transition-all group"
               style="background:{{ $card['bg'] }};border-color:{{ $card['border'] }}">

                <div class="flex items-start justify-between mb-4">

                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                         style="background:{{ $card['bg'] }};border:1px solid {{ $card['border'] }}">
                        {{ $card['icon'] }}
                    </div>

                    <span class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                        {{ $card['label'] }}
                    </span>

                </div>

                <p class="font-[Fraunces] text-2xl xl:text-2xl mb-1"
                   style="color:{{ $card['color'] }}">
                    {{ $card['value'] }}
                </p>

                <p class="font-[IBM_Plex_Mono] text-[12px] text-[#9C9788]">
                    {{ $card['sub'] }}
                </p>

            </a>

        @endforeach

    </div>


    {{-- ========================================================= --}}
    {{-- ===== FINANCE RÉELLE ===== --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

        {{-- BÉNÉFICE NET RÉEL --}}

        <div class="rounded-2xl p-5 border
            {{ $netProfit >= 0 ? 'border-green-400/20' : 'border-red-400/20' }}"
             style="background:{{ $netProfit >= 0 ? 'rgba(34,197,94,0.06)' : 'rgba(239,68,68,0.06)' }}">

            <div class="flex items-start justify-between mb-3">

                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg"
                     style="background:{{ $netProfit >= 0 ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' }}">
                    💹
                </div>

                <span class="font-[IBM_Plex_Mono] text-[12px] uppercase tracking-widest text-[#9C9788]">
                    Bénéfice net réel
                </span>

            </div>

            @if ($hasCostData)

                <p class="font-[Fraunces] text-2xl mb-1
                    {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">

                    {{ $netProfit >= 0 ? '+' : '' }}
                    {{ number_format($netProfit, 0, ',', ' ') }} DA

                </p>

                <p class="font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">

                    Marge:

                    <span class="
                        {{ $netMargin >= 30
                            ? 'text-green-400'
                            : ($netMargin >= 10
                                ? 'text-yellow-400'
                                : 'text-red-400') }}">

                        {{ $netMargin }}%

                    </span>

                    — sur ventes

                </p>

            @else

                <p class="font-[Fraunces] text-xl text-[#9C9788]">
                    —
                </p>

                <p class="font-[IBM_Plex_Mono] text-[12px] text-[#9C9788]">
                    Ajoutez le prix d'achat
                </p>

            @endif

        </div>


        {{-- COÛT DES VENTES --}}

        <div class="rounded-2xl p-5 border border-red-400/20"
             style="background:rgba(239,68,68,0.06)">

            <div class="flex items-start justify-between mb-3">

                <div class="w-9 h-9 rounded-xl bg-red-400/10 flex items-center justify-center text-lg">
                    🏷️
                </div>

                <span class="font-[IBM_Plex_Mono] text-[12px] uppercase tracking-widest text-[#9C9788]">
                    Coût des ventes
                </span>

            </div>

            @if ($hasCostData)

                <p class="font-[Fraunces] text-2xl text-red-400 mb-1">
                    {{ number_format($totalCostSold, 0, ',', ' ') }} DA
                </p>

                <p class="font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">
                    prix d'achat des produits vendus
                </p>

            @else

                <p class="font-[Fraunces] text-xl text-[#9C9788]">
                    —
                </p>

                <p class="font-[IBM_Plex_Mono] text-[12px] text-[#9C9788]">
                    Ajoutez le prix d'achat
                </p>

            @endif

        </div>

    </div>


  {{-- ========================================================= --}}
{{-- ===== PERFORMANCE ===== --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">


{{-- TAUX DE SUCCÈS --}}

<a href="{{ route('admin.orders.index', ['status' => 'paid']) }}"
   class="bg-[#1C1E27] border border-white/10 rounded-2xl p-5
          flex items-center gap-4
          hover:border-green-400/30
          hover:bg-green-400/5
          hover:scale-[1.01]
          transition-all
          cursor-pointer">

    <div class="w-12 h-12 rounded-xl bg-green-400/10
                flex items-center justify-center text-2xl
                flex-shrink-0">

        📈

    </div>

    <div class="min-w-0">

        <p class="font-[IBM_Plex_Mono] text-[12px]
                  uppercase tracking-widest text-[#9C9788]">

            Taux de succès

        </p>

        {{-- CHIFFRE GRAND --}}

        <p class="font-[Fraunces] text-2xl leading-none
                  text-green-400 font-semibold mt-1">

            {{ $successRate }}%

        </p>

        <p class="font-[IBM_Plex_Mono] text-[12px]
                  text-[#9C9788] mt-2">

            {{ $paidOrdersCount }}
            commande{{ $paidOrdersCount != 1 ? 's' : '' }}
            payée{{ $paidOrdersCount != 1 ? 's' : '' }}

        </p>

    </div>

</a>


{{-- PANIER MOYEN --}}

<div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-5
            flex items-center gap-4">

    <div class="w-12 h-12 rounded-xl bg-[#C9A24B]/10
                flex items-center justify-center text-2xl
                flex-shrink-0">

        🧾

    </div>

    <div>

        <p class="font-[IBM_Plex_Mono] text-[12px]
                  uppercase tracking-widest text-[#9C9788]">

            Panier moyen

        </p>

        {{-- CHIFFRE GRAND --}}

        <p class="font-[Fraunces] text-2xl leading-none
                  text-[#C9A24B] font-semibold mt-1">

            {{ number_format($avgOrderValue, 0, ',', ' ') }}

            <span class="text-base">DA</span>

        </p>

        <p class="font-[IBM_Plex_Mono] text-[12px]
                  text-[#9C9788] mt-2">

            par commande payée

        </p>

    </div>

</div>


{{-- ANNULATIONS --}}

<a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
   class="bg-[#1C1E27] border border-white/10 rounded-2xl p-5
          flex items-center gap-4
          hover:border-red-400/30
          hover:bg-red-400/5
          hover:scale-[1.01]
          transition-all
          cursor-pointer">

    <div class="w-12 h-12 rounded-xl bg-red-400/10
                flex items-center justify-center text-2xl
                flex-shrink-0">

        ↩️

    </div>

    <div class="min-w-0">

        <p class="font-[IBM_Plex_Mono] text-[12px]
                  uppercase tracking-widest text-[#9C9788]">

            Annulations

        </p>

        {{-- CHIFFRE GRAND --}}

        <p class="font-[Fraunces] text-2xl leading-none
                  text-red-400 font-semibold mt-1">

            {{ $cancelledOrders }}

        </p>

        <p class="font-[IBM_Plex_Mono] text-[12px]
                  text-[#9C9788] mt-2">

            {{ $cancellationRate }}% du total

        </p>

    </div>

</a>

</div>

    {{-- ========================================================= --}}
    {{-- ===== VALEUR STOCK / BÉNÉFICE PRODUITS ===== --}}
    {{-- ========================================================= --}}

    @if ($stockValue > 0 || $stockPotential > 0)

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

            {{-- VALEUR STOCK --}}

            <div class="rounded-2xl p-6 border"
                 style="background:rgba(59,130,246,0.06);
                        border-color:rgba(59,130,246,0.2)">

                <div class="flex items-start justify-between mb-4">

                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                         style="background:rgba(59,130,246,0.1);
                                border:1px solid rgba(59,130,246,0.2)">

                        🏭

                    </div>

                    <span class="font-[IBM_Plex_Mono] text-[4]
                                 uppercase tracking-widest text-[#9C9788]">

                        Valeur du stock

                    </span>

                </div>

                <p class="font-[Fraunces] text-2xl text-blue-400 mb-1">

                    {{ number_format($stockValue, 0, ',', ' ') }} DA

                </p>

                <p class="font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">

                    capital immobilisé (prix d'achat × stock)

                </p>

            </div>


            {{-- BÉNÉFICE POTENTIEL --}}

            <div class="rounded-2xl p-6 border"
                 style="
                    background:{{ $stockPotential >= 0
                        ? 'rgba(34,197,94,0.06)'
                        : 'rgba(239,68,68,0.06)' }};

                    border-color:{{ $stockPotential >= 0
                        ? 'rgba(34,197,94,0.2)'
                        : 'rgba(239,68,68,0.2)' }}
                 ">

                <div class="flex items-start justify-between mb-4">

                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                         style="
                            background:{{ $stockPotential >= 0
                                ? 'rgba(34,197,94,0.1)'
                                : 'rgba(239,68,68,0.1)' }};

                            border:1px solid {{ $stockPotential >= 0
                                ? 'rgba(34,197,94,0.2)'
                                : 'rgba(239,68,68,0.2)' }}
                         ">

                        💹

                    </div>

                    <span class="font-[IBM_Plex_Mono] text-[4]
                                 uppercase tracking-widest text-[#9C9788]">

                        Bénéfice potentiel

                    </span>

                </div>

                <p class="font-[Fraunces] text-2xl mb-1
                    {{ $stockPotential >= 0
                        ? 'text-green-400'
                        : 'text-red-400' }}">

                    {{ $stockPotential >= 0 ? '+' : '' }}
                    {{ number_format($stockPotential, 0, ',', ' ') }} DA

                </p>

                <p class="font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">

                    si tout le stock est vendu

                </p>

            </div>

        </div>

    @endif


   {{-- ========================================================= --}}
{{-- ===== BÉNÉFICE PAR CATÉGORIE ===== --}}
{{-- ========================================================= --}}

@if (collect($categoryProductStats)->flatten(1)->isNotEmpty())

<div class="bg-[#1C1E27]
            border border-white/10
            rounded-2xl
            overflow-hidden
            mb-6">

    {{-- HEADER --}}

    <div class="flex flex-col sm:flex-row
                sm:items-center
                sm:justify-between
                gap-3
                px-6 py-5
                border-b border-white/8">

        <div>

            <h2 class="font-[Fraunces] text-lg">

                Bénéfice par produit

            </h2>

            <p class="font-[IBM_Plex_Mono] text-[12px]
                      text-[#9C9788] mt-1">

                5 produits par catégorie — basés sur prix d'achat × stock actuel

            </p>

        </div>

        <a href="{{ route('admin.product-profits.index') }}"
   class="font-[IBM_Plex_Mono] text-[12px]
          text-[#C9A24B] hover:underline
          uppercase tracking-wider">

    Voir tous les bénéfices →

</a>

      
       
    </div>


    {{-- CATÉGORIES --}}

    <div class="p-5 space-y-6">

        @foreach (['Homme', 'Femme', 'Bébé', 'Enfants'] as $categoryName)

            @php

                $categoryProducts =
                    $categoryProductStats[$categoryName] ?? [];

            @endphp


            @if (count($categoryProducts) > 0)

                <div>

                    {{-- CATEGORY TITLE --}}

                    <div class="flex items-center justify-between mb-3">

                        <div class="flex items-center gap-3">

                            <div class="w-8 h-8
                                        rounded-lg
                                        bg-[#C9A24B]/10
                                        border border-[#C9A24B]/20
                                        flex items-center justify-center">

                                @if ($categoryName === 'Homme')

                                    👨

                                @elseif ($categoryName === 'Femme')

                                    👩

                                @elseif ($categoryName === 'Bébé')

                                    👶

                                @else

                                    🧒

                                @endif

                            </div>

                            <h3 class="font-[Fraunces] text-base">

                                {{ $categoryName }}

                            </h3>

                        </div>


                        <span class="font-[IBM_Plex_Mono]
                                     text-[10px]
                                     uppercase
                                     tracking-widest
                                     text-[#9C9788]">

                            {{ count($categoryProducts) }} produits

                        </span>

                    </div>


                    {{-- TABLE --}}

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[850px]">

                            <thead>

                                <tr class="border-b border-white/5">

                                    <th class="px-4 py-2.5 text-left
                                               font-[IBM_Plex_Mono]
                                               text-[10px]
                                               uppercase
                                               tracking-widest
                                               text-[#9C9788]">

                                        Produit

                                    </th>

                                    <th class="px-4 py-2.5 text-center
                                               font-[IBM_Plex_Mono]
                                               text-[10px]
                                               uppercase
                                               tracking-widest
                                               text-[#9C9788]">

                                        Stock

                                    </th>

                                    <th class="px-4 py-2.5 text-right
                                               font-[IBM_Plex_Mono]
                                               text-[10px]
                                               uppercase
                                               tracking-widest
                                               text-[#9C9788]">

                                        Prix achat

                                    </th>

                                    <th class="px-4 py-2.5 text-right
                                               font-[IBM_Plex_Mono]
                                               text-[10px]
                                               uppercase
                                               tracking-widest
                                               text-[#9C9788]">

                                        Prix vente

                                    </th>

                                    <th class="px-4 py-2.5 text-right
                                               font-[IBM_Plex_Mono]
                                               text-[10px]
                                               uppercase
                                               tracking-widest
                                               text-[#9C9788]">

                                        Valeur stock

                                    </th>

                                    <th class="px-4 py-2.5 text-right
                                               font-[IBM_Plex_Mono]
                                               text-[10px]
                                               uppercase
                                               tracking-widest
                                               text-[#9C9788]">

                                        Bénéfice

                                    </th>

                                    <th class="px-4 py-2.5 text-center
                                               font-[IBM_Plex_Mono]
                                               text-[10px]
                                               uppercase
                                               tracking-widest
                                               text-[#9C9788]">

                                        Marge

                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($categoryProducts as $ps)

                                    <tr class="border-b border-white/4
                                               hover:bg-white/2
                                               transition-colors">


                                        {{-- PRODUIT --}}

                                        <td class="px-4 py-3">

                                            <p class="font-[Inter]
                                                      text-sm
                                                      font-medium">

                                                {{ $ps['name'] }}

                                            </p>

                                        </td>


                                        {{-- STOCK --}}

                                        <td class="px-4 py-3 text-center">

                                            <span class="font-[IBM_Plex_Mono]
                                                text-xs
                                                {{ $ps['stock'] === 0
                                                    ? 'text-red-400'
                                                    : ($ps['stock'] <= 5
                                                        ? 'text-yellow-400'
                                                        : 'text-[#F6F3EC]') }}">

                                                {{ $ps['stock'] }}

                                            </span>

                                        </td>


                                        {{-- PRIX ACHAT --}}

                                        <td class="px-4 py-3 text-right
                                                   font-[IBM_Plex_Mono]
                                                   text-xs
                                                   text-[#9C9788]">

                                            {{ number_format($ps['cost_price'], 0, ',', ' ') }}
                                            DA

                                        </td>


                                        {{-- PRIX VENTE --}}

                                        <td class="px-4 py-3 text-right
                                                   font-[IBM_Plex_Mono]
                                                   text-xs
                                                   text-[#C9A24B]">

                                            {{ number_format($ps['selling_price'], 0, ',', ' ') }}
                                            DA

                                        </td>


                                        {{-- VALEUR STOCK --}}

                                        <td class="px-4 py-3 text-right
                                                   font-[IBM_Plex_Mono]
                                                   text-xs
                                                   font-bold
                                                   text-blue-400">

                                            {{ number_format($ps['stock_value'], 0, ',', ' ') }}
                                            DA

                                        </td>


                                        {{-- BÉNÉFICE --}}

                                        <td class="px-4 py-3 text-right
                                                   font-[IBM_Plex_Mono]
                                                   text-xs
                                                   font-bold
                                                   {{ $ps['profit'] >= 0
                                                        ? 'text-green-400'
                                                        : 'text-red-400' }}">

                                            {{ $ps['profit'] >= 0 ? '+' : '' }}
                                            {{ number_format($ps['profit'], 0, ',', ' ') }}
                                            DA

                                        </td>


                                        {{-- MARGE --}}

                                        <td class="px-4 py-3 text-center">

                                            <span class="font-[IBM_Plex_Mono]
                                                text-[10px]
                                                px-2 py-0.5
                                                rounded-full
                                                {{ $ps['margin'] >= 30
                                                    ? 'text-green-400 bg-green-400/10'
                                                    : ($ps['margin'] >= 10
                                                        ? 'text-yellow-400 bg-yellow-400/10'
                                                        : 'text-red-400 bg-red-400/10') }}">

                                                {{ $ps['margin'] }}%

                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif

        @endforeach

    </div>


    {{-- FOOTER --}}

    <div class="px-6 py-4
                border-t border-white/8
                flex justify-end">

       <a href="{{ route('admin.products.index') }}"
           class="font-[IBM_Plex_Mono]
                  text-[11px]
                  text-[#C9A24B]
                  hover:underline
                  uppercase
                  tracking-wider">

            Afficher les {{ $stats['total_products'] }} produits →

        </a>

    </div>

</div>

@endif


    {{-- ========================================================= --}}
    {{-- ===== NOTIFICATIONS ===== --}}
    {{-- ========================================================= --}}

    @if ($unreadNotifications->isNotEmpty())

        <div class="bg-[#C9A24B]/5 border border-[#C9A24B]/20
                    rounded-2xl p-6 mb-6">

            <div class="flex items-center justify-between mb-4">

                <h2 class="font-[Fraunces] text-lg">
                    Nouvelles notifications
                </h2>

                <a href="{{ route('admin.notifications.index') }}"
                   class="font-[IBM_Plex_Mono] text-[12px]
                          text-[#C9A24B] hover:underline
                          uppercase tracking-wider">

                    Voir tout →

                </a>

            </div>


            <div class="space-y-2">

                @foreach ($unreadNotifications as $notif)

                    <div class="flex items-center gap-4
                                bg-white/3 rounded-xl px-4 py-3">

                        <span class="text-lg">

                            @if (($notif->data['type'] ?? '') === 'new_order')

                                📦

                            @elseif (($notif->data['type'] ?? '') === 'new_user')

                                👤

                            @else

                                💬

                            @endif

                        </span>


                        <div class="flex-1 min-w-0">

                            <p class="font-[IBM_Plex_Mono] text-xs
                                      text-[#F6F3EC] truncate">

                                {{ $notif->data['title'] ?? '' }}

                            </p>

                            <p class="font-[IBM_Plex_Mono] text-[12px]
                                      text-[#9C9788] truncate">

                                {{ $notif->data['message'] ?? '' }}

                            </p>

                        </div>


                        <span class="font-[IBM_Plex_Mono] text-[12px]
                                     text-[#9C9788] flex-shrink-0">

                            {{ $notif->created_at->diffForHumans() }}

                        </span>

                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ===== DERNIÈRES COMMANDES ===== --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-white/10
                bg-[#1C1E27] overflow-hidden">

        <div class="flex items-center justify-between
                    px-6 py-5 border-b border-white/8">

            <h2 class="font-[Fraunces] text-lg">
                Dernières commandes
            </h2>

            <a href="{{ route('admin.orders.index') }}"
               class="font-[IBM_Plex_Mono] text-[12px]
                      text-[#C9A24B] hover:underline
                      uppercase tracking-wider">

                Voir tout →

            </a>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b border-white/5 bg-white/2">

                        <th class="px-6 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[12px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">
                            N°
                        </th>

                        <th class="px-6 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[12px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">
                            Client
                        </th>

                        <th class="px-6 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[12px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">
                            Total
                        </th>

                        <th class="px-6 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[12px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">
                            Statut
                        </th>

                        <th class="px-6 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[12px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[12px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($recentOrders as $order)

                        @php

                            $sc = match($order->status) {

                                'pending' => [
                                    'label' => 'En attente',
                                    'color' => 'text-yellow-400',
                                    'bg' => 'bg-yellow-400/10',
                                    'bl' => 'border-l-yellow-400'
                                ],

                                'processing' => [
                                    'label' => 'En cours',
                                    'color' => 'text-blue-400',
                                    'bg' => 'bg-blue-400/10',
                                    'bl' => 'border-l-blue-400'
                                ],

                                'paid' => [
                                    'label' => 'Payée ✓',
                                    'color' => 'text-green-400',
                                    'bg' => 'bg-green-400/10',
                                    'bl' => 'border-l-green-400'
                                ],

                                'shipped' => [
                                    'label' => 'Expédiée 🚚',
                                    'color' => 'text-indigo-400',
                                    'bg' => 'bg-indigo-400/10',
                                    'bl' => 'border-l-indigo-400'
                                ],

                                'delivered' => [
                                    'label' => 'Livrée ✅',
                                    'color' => 'text-emerald-400',
                                    'bg' => 'bg-emerald-400/10',
                                    'bl' => 'border-l-emerald-400'
                                ],

                                'cancelled' => [
                                    'label' => 'Annulée',
                                    'color' => 'text-red-400',
                                    'bg' => 'bg-red-400/10',
                                    'bl' => 'border-l-red-400'
                                ],

                                'refunded' => [
                                    'label' => 'Remboursée',
                                    'color' => 'text-orange-400',
                                    'bg' => 'bg-orange-400/10',
                                    'bl' => 'border-l-orange-400'
                                ],

                                default => [
                                    'label' => $order->status,
                                    'color' => 'text-[#9C9788]',
                                    'bg' => 'bg-white/5',
                                    'bl' => 'border-l-transparent'
                                ],

                            };

                        @endphp


                        <tr class="border-b border-white/4
                                   hover:bg-white/2 transition-colors
                                   border-l-2 {{ $sc['bl'] }}">

                            <td class="px-6 py-4
                                       font-[IBM_Plex_Mono] text-xs
                                       text-[#C9A24B]">

                                {{ $order->order_number }}

                            </td>


                            <td class="px-6 py-4">

                                <p class="font-[Inter] text-sm">
                                    {{ $order->shipping_name }}
                                </p>

                                <p class="font-[IBM_Plex_Mono] text-[13px]
                                          text-[#9C9788]">

                                    {{ $order->user?->email
                                        ?? $order->guest_email
                                        ?? '—' }}

                                </p>

                            </td>


                            <td class="px-6 py-4
                                       font-[IBM_Plex_Mono] text-sm
                                       font-bold text-[#C9A24B]">

                                {{ number_format($order->total, 0, ',', ' ') }} DA

                            </td>


                            <td class="px-6 py-4">

                                <span class="font-[IBM_Plex_Mono] text-[10px]
                                             px-2.5 py-1 rounded-full
                                             {{ $sc['color'] }}
                                             {{ $sc['bg'] }}">

                                    {{ $sc['label'] }}

                                </span>

                            </td>


                            <td class="px-6 py-4
                                       font-[IBM_Plex_Mono] text-[14px]
                                       text-[#9C9788]">

                                {{ $order->created_at->format('d/m/Y H:i') }}

                            </td>


                            <td class="px-6 py-4">

                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="font-[IBM_Plex_Mono] text-[13px]
                                          text-[#C9A24B] hover:underline
                                          uppercase tracking-wider">

                                    Voir →

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6"
                                class="px-6 py-12 text-center
                                       font-[IBM_Plex_Mono] text-xs
                                       text-[#9C9788]">

                                Aucune commande

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection