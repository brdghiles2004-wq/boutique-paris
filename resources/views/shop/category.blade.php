@extends('layouts.shop')

@section('title', $category->name . ' — Boutique Paris')

@section('content')

@php
    $isParent = ! $category->parent_id;
@endphp

{{-- Header --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 pb-2">
    <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] uppercase tracking-widest mb-2">
        <a href="{{ route('home') }}"
           class="hover:text-[#C9A24B] transition-colors">
            Boutique Paris
        </a>

        @if ($category->parent_id && $parentCat)
            <span class="mx-1">/</span>

            <a href="{{ route('shop.category', $parentCat) }}"
               class="hover:text-[#C9A24B] transition-colors">
                {{ $parentCat->name }}
            </a>
        @endif

        <span class="mx-1">/</span>

        <span class="text-[#F6F3EC]">
            {{ $category->name }}
        </span>
    </p>

    <h1 class="font-[Fraunces] text-3xl md:text-5xl">
        {{ $category->name }}
    </h1>

    <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] mt-1">
        {{ $products->total() }} articles
    </p>
</div>


{{-- Sous-catégories Mobile --}}
@if ($sidebarLinks->isNotEmpty())

    <div class="md:hidden px-4 py-3 overflow-x-auto">
        <div class="flex gap-2" style="width:max-content">

            <a href="{{ route('shop.category', $parentCat) }}"
               class="flex-shrink-0 font-[IBM_Plex_Mono] text-[11px] px-4 py-2 rounded-full border transition-all
                      {{ $isParent
                          ? 'bg-[#C9A24B] text-[#14151C] border-[#C9A24B] font-bold'
                          : 'border-white/20 text-[#9C9788]' }}">

                Tout voir

            </a>

            @foreach ($sidebarLinks as $child)

                <a href="{{ route('shop.category', $child) }}"
                   class="flex-shrink-0 font-[IBM_Plex_Mono] text-[11px] px-4 py-2 rounded-full border transition-all
                          {{ $category->id === $child->id
                              ? 'bg-[#C9A24B] text-[#14151C] border-[#C9A24B] font-bold'
                              : 'border-white/20 text-[#9C9788]' }}">

                    {{ $child->name }}

                </a>

            @endforeach

        </div>
    </div>

@endif


{{-- Layout --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-10 flex gap-8 md:gap-12">


    {{-- SIDEBAR Desktop --}}
    @if ($sidebarLinks->isNotEmpty())

        <aside class="hidden md:block w-48 flex-shrink-0">

            <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-4 border-b border-white/10 pb-3">
                {{ $parentCat->name }}
            </p>

            <ul class="space-y-0.5">

                <li>

                    <a href="{{ route('shop.category', $parentCat) }}"
                       class="block font-[IBM_Plex_Mono] text-xs py-2 px-3 border-l-2 transition-all
                              {{ $isParent
                                  ? 'border-[#C9A24B] text-[#C9A24B] bg-[#C9A24B]/5'
                                  : 'border-transparent text-[#9C9788] hover:border-[#C9A24B]/50 hover:text-[#F6F3EC]' }}">

                        Tout voir

                    </a>

                </li>


                @foreach ($sidebarLinks as $child)

                    <li>

                        <a href="{{ route('shop.category', $child) }}"
                           class="block font-[IBM_Plex_Mono] text-xs py-2 px-3 border-l-2 transition-all
                                  {{ $category->id === $child->id
                                      ? 'border-[#C9A24B] text-[#C9A24B] bg-[#C9A24B]/5'
                                      : 'border-transparent text-[#9C9788] hover:border-[#C9A24B]/50 hover:text-[#F6F3EC]' }}">

                            {{ $child->name }}

                        </a>

                    </li>

                @endforeach

            </ul>

        </aside>

    @endif


    {{-- PRODUITS --}}
    <div class="flex-1 min-w-0">

        @if ($products->isEmpty())

            {{-- Aucun produit --}}
            <div class="border border-white/10 py-24 text-center rounded-2xl">

                <p class="font-[Fraunces] text-2xl italic text-[#9C9788] mb-2">
                    Ce rayon se prépare.
                </p>

                <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] uppercase tracking-widest">
                    De nouvelles pièces arrivent bientôt.
                </p>

                <a href="{{ route('home') }}"
                   class="inline-block mt-6 font-[IBM_Plex_Mono] text-xs text-[#C9A24B] hover:underline uppercase tracking-widest">

                    ← Retour à l'accueil

                </a>

            </div>


        @elseif ($isParent && $sidebarLinks->isNotEmpty())

            {{-- Catégorie parent → grouper par sous-catégorie --}}
            @php
                $grouped = $products->getCollection()->groupBy('category_id');
            @endphp


            @foreach ($sidebarLinks as $child)

                @php
                    $subcatProducts = $grouped->get($child->id, collect());
                    $displayProducts = $subcatProducts->take(4);
                @endphp


                @if ($subcatProducts->isNotEmpty())

                    <div class="mb-10 md:mb-14">


                        {{-- Titre catégorie --}}
                        <div class="flex items-center justify-between mb-4 md:mb-6">

                            <h2 class="font-[Fraunces] text-xl md:text-2xl">
                                {{ $child->name }}
                            </h2>

                            @if ($subcatProducts->count() >= 4)

                                <a href="{{ route('shop.category', $child) }}"
                                   class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] transition-colors uppercase tracking-wider">

                                    Voir tout →

                                </a>

                            @endif

                        </div>


                        {{-- PRODUITS --}}
                        {{-- Toujours 2 colonnes sur mobile --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5">

                            @foreach ($displayProducts as $product)

                                <div class="min-w-0">

                                    @include('shop.partials.product-card', [
                                        'product' => $product
                                    ])

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif

            @endforeach


        @else

            {{-- Sous-catégorie → grid normal --}}
            {{-- Toujours 2 colonnes sur mobile --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6">

                @foreach ($products as $product)

                    <div class="min-w-0">

                        @include('shop.partials.product-card', [
                            'product' => $product
                        ])

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}
            <div class="mt-10 font-[IBM_Plex_Mono] text-xs
                        [&_a]:text-[#9C9788]
                        [&_a:hover]:text-[#C9A24B]">

                {{ $products->links() }}

            </div>

        @endif

    </div>

</div>

@endsection