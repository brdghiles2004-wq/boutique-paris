@extends('admin.layouts.app')
@section('title', 'Email')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.integrations.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">📧 Email</h1>
    </div>

    <form action="{{ route('admin.integrations.email.save') }}" method="POST" class="max-w-2xl">
        @csrf
        <div class="bg-[#1C1E27] border border-white/10 rounded-xl p-6 space-y-5">

            <div class="flex items-center justify-between mb-2">
                <h2 class="font-[Fraunces] text-lg">Configuration SMTP</h2>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="smtp_enabled" value="1"
                           {{ \App\Models\Setting::get('smtp_enabled') == '1' ? 'checked' : '' }}
                           class="accent-[#C9A24B]">
                    <span class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">Activer SMTP custom</span>
                </label>
            </div>

            <div class="bg-[#C9A24B]/5 border border-[#C9A24B]/20 rounded-lg p-3 mb-4">
                <p>
                    Actuellement: Resend (configuré dans .env). Activez SMTP pour utiliser votre propre serveur.
</p>
            </div>

            @php
                $fields = [
                    ['name' => 'smtp_host',       'label' => 'Hôte SMTP',           'placeholder' => 'smtp.gmail.com'],
                    ['name' => 'smtp_port',        'label' => 'Port',                'placeholder' => '587'],
                    ['name' => 'smtp_username',    'label' => 'Nom d\'utilisateur',  'placeholder' => 'votre@email.com'],
                    ['name' => 'smtp_password',    'label' => 'Mot de passe',        'type' => 'password'],
                    ['name' => 'smtp_from_name',   'label' => 'Nom d\'expéditeur',   'placeholder' => 'Boutique Paris'],
                    ['name' => 'smtp_from_email',  'label' => 'Email d\'expéditeur', 'placeholder' => 'contact@boutique-paris.dz'],
                ];
            @endphp

            @foreach ($fields as $field)
                <div>
                    <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                        {{ $field['label'] }}
                    </label>
                    <input type="{{ $field['type'] ?? 'text' }}"
                           name="{{ $field['name'] }}"
                           value="{{ ($field['type'] ?? 'text') === 'password' ? '' : \App\Models\Setting::get($field['name']) }}"
                           placeholder="{{ $field['placeholder'] ?? ($field['type'] ?? '' === 'password' ? '••••••••' : '') }}"
                           class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs">
                </div>
            @endforeach

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Chiffrement</label>
                <select name="smtp_encryption"
                        class="w-full bg-[#14151C] border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">
                    <option value="tls" {{ \App\Models\Setting::get('smtp_encryption', 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ \App\Models\Setting::get('smtp_encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="" {{ \App\Models\Setting::get('smtp_encryption') === '' ? 'selected' : '' }}>Aucun</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="px-6 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors">
                Enregistrer
            </button>
        </div>
    </form>
@endsection