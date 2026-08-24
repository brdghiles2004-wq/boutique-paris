@extends('layouts.shop')

@section('title', 'Créer un compte — Boutique Paris')

@section('content')

    <div class="max-w-md mx-auto px-6 py-20">
        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4 text-center">
            Bienvenue
        </p>
        <h1 class="font-[Fraunces] text-4xl text-center mb-12">Créer un compte</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
                @error('name') <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Adresse e-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
                @error('email') <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Mot de passe</label>
                <input type="password" name="password" required autocomplete="new-password"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
                @error('password') <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
                @error('password_confirmation') <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full px-6 py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564] transition-colors">
                Créer mon compte
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('auth.google') }}"
               class="w-full inline-flex justify-center items-center px-6 py-3 border border-white/20 font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:border-[#C9A24B] hover:text-[#C9A24B] transition-colors">
                Continuer avec Google
            </a>
        </div>

        <p class="mt-8 text-center font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
            Déjà un compte ?
            <a href="{{ route('login') }}" class="text-[#C9A24B] hover:underline">Se connecter</a>
        </p>
    </div>

@endsection