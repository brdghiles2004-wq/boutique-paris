@extends('layouts.shop')

@section('title', $product->name . ' — Boutique Paris')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-2 gap-16">

        <div class="aspect-[3/4] bg-[#1C1E27] flex items-center justify-center">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <span class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] uppercase tracking-widest">Photo à venir</span>
            @endif
        </div>

        <div>
            <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] uppercase tracking-widest mb-3">
                {{ $product->category->name }}
            </p>
            <h1 class="font-[Fraunces] text-4xl mb-4">{{ $product->name }}</h1>

            <p class="font-[IBM_Plex_Mono] text-xl text-[#C9A24B] mb-8">
                @if ($product->sale_price)
                    <span class="line-through text-[#9C9788] mr-3 text-base">{{ number_format($product->price, 0, ',', ' ') }} DA</span>
                    {{ number_format($product->sale_price, 0, ',', ' ') }} DA
                @else
                    {{ number_format($product->price, 0, ',', ' ') }} DA
                @endif
            </p>

            @if ($product->description)
                <p class="text-[#9C9788] leading-relaxed mb-10">{{ $product->description }}</p>
            @endif

            <h2 class="font-[Fraunces] text-lg mb-4">Choisir une variante</h2>

            <div class="space-y-3">
                @forelse ($product->variants as $variant)
                    <form action="{{ route('cart.add', $variant) }}" method="POST"
                          class="flex items-center justify-between border border-white/10 px-4 py-3 {{ ! $variant->in_stock ? 'opacity-40' : 'hover:border-[#C9A24B]/50' }} transition-colors">
                        @csrf
                        <span class="font-[IBM_Plex_Mono] text-xs uppercase tracking-wider">{{ $variant->label }}</span>

                        @if ($variant->in_stock)
                            <div class="flex items-center gap-3">
                                <input type="number" name="quantity" value="1" min="1" max="{{ $variant->stock }}"
                                       class="w-14 bg-transparent border border-white/20 text-center font-[IBM_Plex_Mono] text-xs py-1">
                                <button type="submit"
                                        class="px-4 py-1.5 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest hover:bg-[#dab564]">
                                    Ajouter
                                </button>
                            </div>
                        @else
                            <span class="font-[IBM_Plex_Mono] text-[11px] text-[#7A2E3A] uppercase tracking-widest">Épuisé</span>
                        @endif
                    </form>
                @empty
                    <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Aucune variante disponible pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <div class="max-w-7xl mx-auto px-6 py-20 border-t border-white/10">
            <h2 class="font-[Fraunces] text-2xl mb-10">Vous aimerez aussi</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ($relatedProducts as $related)
                    @include('shop.partials.product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
    @endif

@endsection