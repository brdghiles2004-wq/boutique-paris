@extends('layouts.shop')

@section('title', 'Mon panier — Boutique Paris')

@section('content')

    <div class="max-w-4xl mx-auto px-6 py-16">
        <h1 class="font-[Fraunces] text-4xl mb-12">Mon panier</h1>

        @if ($cart->items->isEmpty())
            <div class="border border-white/10 py-24 text-center">
                <p class="font-[Fraunces] text-2xl mb-2">Votre panier est vide.</p>
                <a href="{{ route('home') }}" class="font-[IBM_Plex_Mono] text-xs uppercase tracking-widest text-[#C9A24B] hover:underline">
                    Découvrir la collection →
                </a>
            </div>
        @else
            <div class="divide-y divide-white/10 border-t border-b border-white/10">
                @foreach ($cart->items as $item)
                    <div class="py-6 flex items-center gap-6">
                        <div class="w-20 h-24 bg-[#1C1E27] flex-shrink-0 flex items-center justify-center">
                            @if ($item->variant->product->image)
                                <img src="{{ asset('storage/' . $item->variant->product->image) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-[Fraunces] text-lg">{{ $item->variant->product->name }}</h3>
                            <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] uppercase tracking-wider mt-1">
                                {{ $item->variant->label }}
                            </p>
                        </div>
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->variant->stock }}"
                                   class="w-14 bg-transparent border border-white/20 text-center font-[IBM_Plex_Mono] text-xs py-1">
                            <button type="submit" class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] hover:text-[#C9A24B] uppercase">Maj</button>
                        </form>
                        <p class="font-[IBM_Plex_Mono] text-sm w-28 text-right">{{ number_format($item->subtotal, 0, ',', ' ') }} DA</p>
                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-[IBM_Plex_Mono] text-[11px] text-[#7A2E3A] hover:underline">Retirer</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 flex justify-end">
                <div class="w-full max-w-xs">
                    <div class="flex justify-between font-[IBM_Plex_Mono] text-sm mb-6">
                        <span class="text-[#9C9788] uppercase tracking-widest text-xs">Total</span>
                        <span class="text-[#C9A24B] text-lg">{{ number_format($cart->total, 0, ',', ' ') }} DA</span>
                    </div>
                    <a href="{{ route('checkout.index') }}"
                       class="block text-center px-6 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564]">
                        Passer la commande
                    </a>
                </div>
            </div>
        @endif
    </div>

@endsection