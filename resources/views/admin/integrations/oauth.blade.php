@extends('admin.layouts.app')
@section('title', 'OAuth & Authentification')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.integrations.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">🔐 OAuth & Authentification</h1>
    </div>

    <form action="{{ route('admin.integrations.oauth.save') }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf

        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-400/15 flex items-center justify-center">
                    <span>🔵</span>
                </div>
                <div>
                    <h2 class="font-[Fraunces] text-lg">Google OAuth</h2>
                    <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">Connexion avec compte Google</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                @foreach([
                    ['key'=>'google_client_id',     'label'=>'Client ID',      'type'=>'text'],
                    ['key'=>'google_client_secret', 'label'=>'Client Secret',  'type'=>'password'],
                    ['key'=>'google_redirect_uri',  'label'=>'Redirect URI',   'type'=>'text', 'placeholder'=>'https://votresite.com/auth/google/callback'],
                ] as $f)
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            {{ $f['label'] }}
                        </label>
                        <input type="{{ $f['type'] }}" name="{{ $f['key'] }}"
                               value="{{ $f['type'] !== 'password' ? \App\Models\Setting::get($f['key']) : '' }}"
                               placeholder="{{ $f['placeholder'] ?? ($f['type'] === 'password' ? '••••••••' : '') }}"
                               class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl text-[#F6F3EC]">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#C9A24B]/15 flex items-center justify-center">
                    <span>🌐</span>
                </div>
                <div>
                    <h2 class="font-[Fraunces] text-lg">URL du site</h2>
                    <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">Utilisée pour les redirections et emails</p>
                </div>
            </div>
            <div class="p-6">
                <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                    APP URL
                </label>
                <input type="url" name="app_url"
                       value="{{ \App\Models\Setting::get('app_url', config('app.url')) }}"
                       placeholder="https://votresite.com"
                       class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl text-[#F6F3EC]">
            </div>
        </div>

        <button type="submit"
                class="px-8 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-xl hover:bg-[#dab564] transition-colors">
            Enregistrer
        </button>
    </form>
@endsection