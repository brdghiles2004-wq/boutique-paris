@extends('layouts.shop')

@section('title', 'Paiement Crypto — Boutique Paris')

@section('content')

    <div class="max-w-lg mx-auto px-6 py-20 text-center">
        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4">
            Paiement Crypto
        </p>
        <h1 class="font-[Fraunces] text-3xl mb-2">Envoyez votre paiement</h1>
        <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] mb-12">
            Commande {{ $order->order_number }}
        </p>

        {{-- المبلغ --}}
        <div class="border border-white/10 p-6 mb-6">
            <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-2">Montant à envoyer</p>
            <p class="font-[Fraunces] text-4xl text-[#C9A24B]">{{ $paymentData['pay_amount'] }}</p>
            <p class="font-[IBM_Plex_Mono] text-sm text-[#9C9788] mt-1">{{ strtoupper($paymentData['pay_currency']) }}</p>
            <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] mt-3 border-t border-white/10 pt-3">
                Équivalent: {{ number_format($order->total, 0, ',', ' ') }} DA
            </p>
        </div>

        {{-- العنوان --}}
        <div class="border border-[#C9A24B]/30 bg-[#C9A24B]/5 p-6 mb-6">
            <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-3">Adresse de paiement</p>
            <p class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC] break-all mb-4">{{ $paymentData['pay_address'] }}</p>
            <button onclick="navigator.clipboard.writeText('{{ $paymentData['pay_address'] }}').then(() => this.textContent = 'Copié ✅')"
                    class="px-4 py-2 border border-[#C9A24B] text-[#C9A24B] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#C9A24B] hover:text-[#14151C] transition-colors">
                Copier l'adresse
            </button>
        </div>

        {{-- Réseau --}}
        <div class="border border-white/10 p-4 mb-8">
            <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-1">Réseau</p>
            <p class="font-[IBM_Plex_Mono] text-sm text-[#F6F3EC]">{{ strtoupper($paymentData['network']) }} (TRC20)</p>
        </div>

        {{-- تحذير --}}
        <div class="bg-[#7A2E3A]/20 border border-[#7A2E3A]/40 p-4 mb-8 text-left">
            <p class="font-[IBM_Plex_Mono] text-xs text-[#e9b3bb]">
                ⚠️ Envoyez <strong>exactement {{ $paymentData['pay_amount'] }} {{ strtoupper($paymentData['pay_currency']) }}</strong> sur le réseau <strong>TRC20</strong> uniquement. Tout autre réseau entraînera une perte de fonds.
            </p>
        </div>

        {{-- انتهاء الصلاحية --}}
        <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] mb-8">
            Valable jusqu'au {{ \Carbon\Carbon::parse($paymentData['valid_until'])->format('d/m/Y à H:i') }}
        </p>

        <a href="{{ route('home') }}"
           class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] hover:text-[#C9A24B]">
            ← Retour à la boutique
        </a>
    </div>

@endsection