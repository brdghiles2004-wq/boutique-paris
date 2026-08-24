@extends('layouts.shop')
 
@section('title', 'Boutique Paris — Mode pour toute la famille')
 
@section('content')
 
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14 md:py-32">
            <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4 md:mb-6">
                Nouvelle collection
            </p>
            <h1 class="font-[Fraunces] text-4xl md:text-7xl leading-[1.05] max-w-3xl">
                Habillez votre monde,
                <span class="italic text-[#C9A24B]">du nouveau-né à l'adulte.</span>
            </h1>
            <p class="mt-4 md:mt-6 max-w-xl text-[#9C9788] text-base md:text-lg">
                Une garde-robe pensée pour chaque membre de la famille.
            </p>
        </div>
    </section>
 
    {{-- Section par catégorie --}}
    @forelse ($featuredByCategory as $item)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-16 border-b border-white/10">
            <div class="flex items-end justify-between mb-6 md:mb-10">
                <div>
                    <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#C9A24B] mb-1 md:mb-2">
                        Sélection
                    </p>
                    <a href="{{ route('shop.category', $item['category']) }}"
                       class="font-[Fraunces] text-2xl md:text-3xl hover:text-[#C9A24B] transition-colors">
                        {{ $item['category']->name }}
                    </a>
                </div>
                <a href="{{ route('shop.category', $item['category']) }}"
                   class="font-[IBM_Plex_Mono] text-xs uppercase tracking-widest text-[#9C9788] hover:text-[#C9A24B] transition-colors whitespace-nowrap ml-3">
                    Voir tout →
                </a>
            </div>
 
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
                @foreach ($item['products'] as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @empty
        <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-px bg-white/10">
                @foreach ($categories as $i => $category)
                    <a href="{{ route('shop.category', $category) }}"
                       class="group bg-[#14151C] hover:bg-[#1C1E27] p-6 flex flex-col justify-between min-h-[220px] transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">N°{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="w-6 h-px bg-[#C9A24B]/50 group-hover:w-10 transition-all"></span>
                        </div>
                        <div>
                            <h3 class="font-[Fraunces] text-2xl mb-1">{{ $category->name }}</h3>
                            <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] uppercase tracking-wider">
                                {{ $category->children->count() }} rayons
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
            <p class="text-center font-[IBM_Plex_Mono] text-xs text-[#9C9788] mt-12 uppercase tracking-widest">
                Les produits vedettes apparaîtront ici dès qu'ils seront ajoutés.
            </p>
        </section>
    @endforelse
 
@endsection