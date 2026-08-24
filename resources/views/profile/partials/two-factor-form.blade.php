@php
    $user = auth()->user();
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Vérification en 2 étapes
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ajoutez une couche de sécurité supplémentaire à votre compte avec une application d'authentification (Google Authenticator, Authy...).
        </p>
    </header>

    <div class="mt-6">
        @if ($user->two_factor_enabled)
            <p class="text-sm text-green-600 dark:text-green-400 mb-4">✓ La vérification en 2 étapes est activée sur votre compte.</p>

            <form method="POST" action="{{ route('two-factor.disable') }}">
                @csrf
                <x-secondary-button type="submit">
                    {{ __('Désactiver') }}
                </x-secondary-button>
            </form>
        @else
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">La vérification en 2 étapes n'est pas activée.</p>

            <a href="{{ route('two-factor.setup') }}">
                <x-primary-button type="button">
                    {{ __('Activer') }}
                </x-primary-button>
            </a>
        @endif
    </div>
</section>