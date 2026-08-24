@extends('layouts.shop')

@section('title', 'Paiement confirmé — Boutique Paris')

@section('content')

    <div class="max-w-lg mx-auto px-6 py-32 text-center">
        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-6">
            Confirmé
        </p>
        <h1 class="font-[Fraunces] text-4xl mb-6">Merci pour votre commande !</h1>
        <p class="font-[IBM_Plex_Mono] text-sm text-[#9C9788] leading-relaxed mb-12">
            Votre paiement est en cours de traitement. Vous recevrez une confirmation par e-mail une fois validé.
        </p>
        <a href="{{ route('home') }}"
           class="inline-block px-8 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564] transition-colors">
            Retour à la boutique
        </a>
    </div>

@endsection