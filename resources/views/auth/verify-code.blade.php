@extends('layouts.shop')

@section('title', 'Vérification de votre e-mail — Boutique Paris')

@section('content')

    <div class="max-w-md mx-auto px-6 py-24">
        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4 text-center">
            Dernière étape
        </p>
        <h1 class="font-[Fraunces] text-3xl text-center mb-4">Vérifiez votre e-mail</h1>
        <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] text-center mb-12 leading-relaxed">
            Un code à 6 chiffres a été envoyé à votre adresse e-mail. Entrez-le ci-dessous.
        </p>

        @if (session('error'))
            <p class="text-[#e9b3bb] text-xs text-center mb-6 font-[IBM_Plex_Mono]">{{ session('error') }}</p>
        @endif

        @if (session('success'))
            <p class="text-[#C9A24B] text-xs text-center mb-6 font-[IBM_Plex_Mono]">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('verification.code.verify') }}" class="space-y-6">
            @csrf

            <input type="text" name="code" inputmode="numeric" maxlength="6" required autofocus
                   class="w-full bg-transparent border border-white/20 px-4 py-3 text-center text-2xl tracking-[0.5em] font-[IBM_Plex_Mono] focus:border-[#C9A24B] outline-none">
            @error('code') <p class="text-[#e9b3bb] text-xs mt-2 text-center">{{ $message }}</p> @enderror

            <button type="submit"
                    class="w-full px-6 py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564] transition-colors">
                Confirmer
            </button>
        </form>

        <form method="POST" action="{{ route('verification.code.resend') }}" class="mt-6 text-center">
            @csrf
            <button type="submit" class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] hover:text-[#C9A24B] underline">
                Je n'ai pas reçu le code, renvoyer
            </button>
        </form>
    </div>

@endsection