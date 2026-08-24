@extends('admin.layouts.app')

@section('title', 'Bénéfice par produit')

@section('content')

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">

        <div>

            <div class="flex items-center gap-3 mb-2">

                <a href="{{ route('admin.dashboard') }}"
                   class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] hover:text-[#C9A24B] transition-colors">

                    ← Dashboard

                </a>

            </div>

            <h1 class="font-[Fraunces] text-3xl">
                Bénéfice par produit
            </h1>

            <p class="font-[IBM_Plex_Mono] text-[13px] text-[#9C9788] mt-1">
                Tous les produits — classés de A à Z
            </p>

        </div>


        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center justify-center px-5 py-3 rounded-xl
                  bg-[#C9A24B] text-[#14151C]
                  font-[IBM_Plex_Mono] text-xs uppercase tracking-wider
                  hover:bg-[#dab564] transition-colors">

            Gérer produits →

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- RÉSUMÉ --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">


        {{-- VALEUR STOCK --}}

        <div class="rounded-2xl p-6 border"
             style="
                background:rgba(59,130,246,0.06);
                border-color:rgba(59,130,246,0.2)
             ">

            <div class="flex items-start justify-between mb-4">

                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                     style="
                        background:rgba(59,130,246,0.1);
                        border:1px solid rgba(59,130,246,0.2)
                     ">

                    🏭

                </div>

                <span class="font-[IBM_Plex_Mono] text-[11px]
                             uppercase tracking-widest text-[#9C9788]">

                    Valeur du stock

                </span>

            </div>

            <p class="font-[Fraunces] text-2xl text-blue-400 mb-1">

                {{ number_format($stockValue, 0, ',', ' ') }} DA

            </p>

            <p class="font-[IBM_Plex_Mono] text-[12px] text-[#9C9788]">

                Prix d'achat × stock actuel

            </p>

        </div>


        {{-- BÉNÉFICE POTENTIEL --}}

        <div class="rounded-2xl p-6 border"
             style="
                background:rgba(34,197,94,0.06);
                border-color:rgba(34,197,94,0.2)
             ">

            <div class="flex items-start justify-between mb-4">

                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                     style="
                        background:rgba(34,197,94,0.1);
                        border:1px solid rgba(34,197,94,0.2)
                     ">

                    💹

                </div>

                <span class="font-[IBM_Plex_Mono] text-[11px]
                             uppercase tracking-widest text-[#9C9788]">

                    Bénéfice potentiel

                </span>

            </div>

            <p class="font-[Fraunces] text-2xl text-green-400 mb-1">

                {{ $stockPotential >= 0 ? '+' : '' }}
                {{ number_format($stockPotential, 0, ',', ' ') }} DA

            </p>

            <p class="font-[IBM_Plex_Mono] text-[12px] text-[#9C9788]">

                Si tout le stock est vendu

            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTRES --}}
    {{-- ========================================================= --}}

    <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-4 mb-6">

        <form method="GET"
              action="{{ route('admin.product-profits.index') }}"
              class="grid grid-cols-1 md:grid-cols-[1fr_220px_auto] gap-3">


            {{-- RECHERCHE --}}

            <div>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Rechercher un produit..."
                    class="w-full rounded-xl
                           bg-white/5
                           border border-white/10
                           px-4 py-3
                           font-[IBM_Plex_Mono]
                           text-sm
                           text-[#F6F3EC]
                           placeholder:text-[#6f6b60]
                           focus:outline-none
                           focus:border-[#C9A24B]/50"
                >

            </div>


            {{-- CATÉGORIE --}}

            <div>

                <select
                    name="category"
                    class="w-full rounded-xl
                           bg-[#14151C]
                           border border-white/10
                           px-4 py-3
                           font-[IBM_Plex_Mono]
                           text-sm
                           text-[#F6F3EC]
                           focus:outline-none
                           focus:border-[#C9A24B]/50"
                >

                    <option value="">
                        Toutes les catégories
                    </option>

                    @foreach ($categories as $category)

                        <option value="{{ $category }}"
                            {{ $categoryFilter === $category ? 'selected' : '' }}>

                            {{ $category }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- BOUTON --}}

            <button
                type="submit"
                class="rounded-xl
                       px-5 py-3
                       bg-[#C9A24B]
                       text-[#14151C]
                       font-[IBM_Plex_Mono]
                       text-xs
                       uppercase
                       tracking-wider
                       hover:bg-[#dab564]
                       transition-colors">

                Filtrer

            </button>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- TABLEAU --}}
    {{-- ========================================================= --}}

    <div class="bg-[#1C1E27]
                border border-white/10
                rounded-2xl
                overflow-hidden">


        {{-- HEADER TABLEAU --}}

        <div class="flex flex-col sm:flex-row
                    sm:items-center
                    sm:justify-between
                    gap-2
                    px-6 py-5
                    border-b border-white/8">

            <div>

                <h2 class="font-[Fraunces] text-lg">

                    Tous les produits

                </h2>

                <p class="font-[IBM_Plex_Mono] text-[12px]
                          text-[#9C9788] mt-1">

                    {{ count($productStats) }} produit(s)

                </p>

            </div>

            <span class="font-[IBM_Plex_Mono] text-[11px]
                         uppercase tracking-widest
                         text-[#9C9788]">

                A → Z

            </span>

        </div>


        {{-- TABLE --}}

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead>

                    <tr class="border-b border-white/5 bg-white/2">


                        <th class="px-5 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Produit

                        </th>


                        <th class="px-5 py-3 text-left
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Catégorie

                        </th>


                        <th class="px-5 py-3 text-center
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Stock

                        </th>


                        <th class="px-5 py-3 text-right
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Prix achat

                        </th>


                        <th class="px-5 py-3 text-right
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Prix vente

                        </th>


                        <th class="px-5 py-3 text-right
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Valeur stock

                        </th>


                        <th class="px-5 py-3 text-right
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Bénéfice pot.

                        </th>


                        <th class="px-5 py-3 text-center
                                   font-[IBM_Plex_Mono] text-[11px]
                                   uppercase tracking-widest
                                   text-[#9C9788]">

                            Marge

                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($productStats as $ps)

                        <tr class="border-b border-white/4
                                   hover:bg-white/2
                                   transition-colors">


                            {{-- PRODUIT --}}

                            <td class="px-5 py-4">

                                <p class="font-[Inter]
                                          text-sm
                                          font-medium
                                          text-[#F6F3EC]">

                                    {{ $ps['name'] }}

                                </p>

                            </td>


                            {{-- CATÉGORIE --}}

                            <td class="px-5 py-4">

                                <span class="font-[IBM_Plex_Mono]
                                             text-[11px]
                                             px-2.5 py-1
                                             rounded-full
                                             bg-white/5
                                             text-[#C9A24B]">

                                    {{ $ps['category_name'] }}

                                </span>

                            </td>


                            {{-- STOCK --}}

                            <td class="px-5 py-4 text-center">

                                <span class="font-[IBM_Plex_Mono]
                                    text-sm
                                    {{ $ps['stock'] === 0
                                        ? 'text-red-400'
                                        : ($ps['stock'] <= 5
                                            ? 'text-yellow-400'
                                            : 'text-[#F6F3EC]') }}">

                                    {{ $ps['stock'] }}

                                </span>

                            </td>


                            {{-- PRIX ACHAT --}}

                            <td class="px-5 py-4 text-right
                                       font-[IBM_Plex_Mono]
                                       text-xs
                                       text-[#9C9788]">

                                @if ($ps['cost_price'] > 0)

                                    {{ number_format($ps['cost_price'], 0, ',', ' ') }} DA

                                @else

                                    —

                                @endif

                            </td>


                            {{-- PRIX VENTE --}}

                            <td class="px-5 py-4 text-right
                                       font-[IBM_Plex_Mono]
                                       text-xs
                                       text-[#C9A24B]">

                                @if ($ps['selling_price'] > 0)

                                    {{ number_format($ps['selling_price'], 0, ',', ' ') }} DA

                                @else

                                    —

                                @endif

                            </td>


                            {{-- VALEUR STOCK --}}

                            <td class="px-5 py-4 text-right
                                       font-[IBM_Plex_Mono]
                                       text-sm
                                       font-bold
                                       text-blue-400">

                                {{ number_format($ps['stock_value'], 0, ',', ' ') }} DA

                            </td>


                            {{-- BÉNÉFICE --}}

                            <td class="px-5 py-4 text-right
                                       font-[IBM_Plex_Mono]
                                       text-sm
                                       font-bold
                                       {{ $ps['profit'] >= 0
                                            ? 'text-green-400'
                                            : 'text-red-400' }}">

                                {{ $ps['profit'] >= 0 ? '+' : '' }}
                                {{ number_format($ps['profit'], 0, ',', ' ') }} DA

                            </td>


                            {{-- MARGE --}}

                            <td class="px-5 py-4 text-center">

                                <span class="font-[IBM_Plex_Mono]
                                    text-xs
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

                    @empty

                        <tr>

                            <td colspan="8"
                                class="px-6 py-16 text-center">

                                <p class="text-3xl mb-3">
                                    📦
                                </p>

                                <p class="font-[Fraunces]
                                          text-lg
                                          text-[#C9A24B]">

                                    Aucun produit trouvé

                                </p>

                                <p class="font-[IBM_Plex_Mono]
                                          text-xs
                                          text-[#9C9788]
                                          mt-2">

                                    Modifiez votre recherche ou votre filtre.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                {{-- TOTAL --}}

                @if (count($productStats) > 0)

                    <tfoot>

                        <tr class="border-t border-white/10
                                   bg-white/3">

                            <td colspan="5"
                                class="px-5 py-4
                                       font-[IBM_Plex_Mono]
                                       text-xs
                                       text-[#9C9788]
                                       uppercase
                                       tracking-wider">

                                Total

                            </td>


                            <td class="px-5 py-4 text-right
                                       font-[IBM_Plex_Mono]
                                       text-sm
                                       font-bold
                                       text-blue-400">

                                {{ number_format($stockValue, 0, ',', ' ') }} DA

                            </td>


                            <td class="px-5 py-4 text-right
                                       font-[IBM_Plex_Mono]
                                       text-sm
                                       font-bold
                                       {{ $stockPotential >= 0
                                            ? 'text-green-400'
                                            : 'text-red-400' }}">

                                {{ $stockPotential >= 0 ? '+' : '' }}
                                {{ number_format($stockPotential, 0, ',', ' ') }} DA

                            </td>


                            <td></td>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>

    </div>

@endsection