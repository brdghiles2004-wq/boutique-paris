@extends('admin.layouts.app')
@section('title', 'Communication')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.integrations.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">📱 Communication</h1>
    </div>

    <form action="{{ route('admin.integrations.communication.save') }}" method="POST" class="max-w-2xl">
        @csrf
        <div class="bg-[#1C1E27] border border-white/10 rounded-xl p-6 space-y-5">

            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💬</span>
                    <h2 class="font-[Fraunces] text-lg">WhatsApp Business</h2>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="whatsapp_enabled" value="1"
                           {{ \App\Models\Setting::get('whatsapp_enabled') == '1' ? 'checked' : '' }}
                           class="accent-[#C9A24B]">
                    <span class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">Activer</span>
                </label>
            </div>

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                    Numéro WhatsApp (avec indicatif, ex: 213XXXXXXXXX)
                </label>
                <input type="text" name="whatsapp_number"
                       value="{{ \App\Models\Setting::get('whatsapp_number') }}"
                       placeholder="213XXXXXXXXX"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs">
            </div>

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                    Message d'accueil (pré-rempli)
                </label>
                <textarea name="whatsapp_message" rows="3"
                          class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs">{{ \App\Models\Setting::get('whatsapp_message', 'Bonjour, j\'ai une question sur ma commande...') }}</textarea>
            </div>

            <div class="bg-[#C9A24B]/5 border border-[#C9A24B]/20 rounded-lg p-3">
                <h1> ✅ Un bouton WhatsApp flottant sera affiché sur toutes les pages du site quand activé.
                 </h1>
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