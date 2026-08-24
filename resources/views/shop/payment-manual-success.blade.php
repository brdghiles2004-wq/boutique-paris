@extends('layouts.shop')

@section('title', 'Commande confirmée — Boutique Paris')

@section('content')

    <div class="max-w-lg mx-auto px-6 py-32 text-center">
        <div class="w-16 h-16 rounded-full bg-[#C9A24B]/10 border border-[#C9A24B]/30 flex items-center justify-center mx-auto mb-8">
            <span class="text-3xl">✓</span>
        </div>

        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4">
            Commande reçue
        </p>
        <h1 class="font-[Fraunces] text-4xl mb-4">Merci !</h1>
        <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] mb-2">
            Commande <span class="text-[#C9A24B]">{{ $order->order_number }}</span>
        </p>

        @if (session('success'))
            <p class="font-[IBM_Plex_Mono] text-sm text-[#F6F3EC] leading-relaxed mt-6 mb-8">
                {{ session('success') }}
            </p>
        @endif

        <div class="bg-[#1C1E27] border border-white/10 rounded-xl p-6 text-left mb-8">
            <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-4">Récapitulatif</p>
            <div class="space-y-2 font-[IBM_Plex_Mono] text-xs">
                <div class="flex justify-between">
                    <span class="text-[#9C9788]">Total</span>
                    <span class="text-[#C9A24B]">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#9C9788]">Livraison</span>
                    <span>{{ $order->delivery_type === 'stop_desk' ? 'Stop Desk' : 'À Domicile' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#9C9788]">Wilaya</span>
                    <span>{{ $order->shipping_wilaya }}</span>
                </div>
            </div>
        </div>

        <a href="{{ route('home') }}"
           class="inline-block px-8 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors">
            Retour à la boutique
        </a>
    </div>

@endsection