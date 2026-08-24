@extends('layouts.shop')

@section('title', 'Connexion — Boutique Paris')

@section('content')

    <div class="max-w-md mx-auto px-6 py-20">
        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4 text-center">
            Bon retour
        </p>
        <h1 class="font-[Fraunces] text-4xl text-center mb-12">Se connecter</h1>

        @if (session('status'))
            <p class="text-[#C9A24B] text-xs text-center mb-6 font-[IBM_Plex_Mono]">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                    Adresse e-mail
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
                @error('email') <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                        Mot de passe
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] hover:text-[#C9A24B]">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>
                <input type="password" name="password" required autocomplete="current-password"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
                @error('password') <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="remember" id="remember"
                       class="w-4 h-4 accent-[#C9A24B]">
                <label for="remember" class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit"
                    class="w-full px-6 py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564] transition-colors">
                Se connecter
            </button>
        </form>

        <div class="mt-6">
            <a href="{{ route('auth.google') }}"
               class="w-full inline-flex justify-center items-center px-6 py-3 border border-white/20 font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:border-[#C9A24B] hover:text-[#C9A24B] transition-colors">
                Continuer avec Google
            </a>
        </div>

        <p class="mt-8 text-center font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-[#C9A24B] hover:underline">S'inscrire</a>
        </p>
    </div>

@endsection