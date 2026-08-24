@extends('layouts.shop')

@section('title', 'Activer la vérification en 2 étapes — Boutique Paris')

@section('content')

    <div class="max-w-md mx-auto px-6 py-20">
        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4 text-center">
            Sécurité
        </p>
        <h1 class="font-[Fraunces] text-3xl text-center mb-4">Vérification en 2 étapes</h1>
        <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] text-center mb-10 leading-relaxed">
            Scannez ce code avec Google Authenticator ou Authy, puis entrez le code à 6 chiffres pour confirmer.
        </p>

        @if (session('error'))
            <p class="text-[#e9b3bb] text-xs text-center mb-6 font-[IBM_Plex_Mono]">{{ session('error') }}</p>
        @endif

        <div class="bg-white p-6 flex justify-center mb-8">
            {!! $qrCodeUrl !!}
        </div>

        <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] text-center mb-10 break-all">
            ولا أدخل هاد الكود يدوياً: {{ $secret }}
        </p>

        <form method="POST" action="{{ route('two-factor.enable') }}" class="space-y-6">
            @csrf

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2 text-center">
                    Code à 6 chiffres
                </label>
                <input type="text" name="code" inputmode="numeric" maxlength="6" required autofocus
                       class="w-full bg-transparent border border-white/20 px-4 py-3 text-center text-2xl tracking-[0.5em] font-[IBM_Plex_Mono] focus:border-[#C9A24B] outline-none">
                @error('code') <p class="text-[#e9b3bb] text-xs mt-2 text-center">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full px-6 py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564] transition-colors">
                Confirmer et activer
            </button>
        </form>

        <p class="mt-8 text-center">
            <a href="{{ route('profile.edit') }}" class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] hover:text-[#C9A24B]">
                ← Annuler
            </a>
        </p>
    </div>

@endsection