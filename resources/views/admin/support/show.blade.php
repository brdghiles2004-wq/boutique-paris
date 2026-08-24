@extends('admin.layouts.app')
@section('title', 'Message: ' . $message->subject)

@section('content')

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.support.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">{{ $message->subject }}</h1>
    </div>

    <div class="space-y-6">

        {{-- ===== MESSAGE CLIENT ===== --}}
        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#C9A24B]/15 border border-[#C9A24B]/30 flex items-center justify-center flex-shrink-0">
                        <span class="font-[Fraunces] text-lg text-[#C9A24B] font-bold">
                            {{ strtoupper(substr($message->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-[Inter] text-base font-semibold">{{ $message->name }}</p>
                        <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">{{ $message->email }}</p>
                    </div>
                </div>
                <div class="text-right space-y-1">
                    <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                        {{ $message->created_at->format('d/m/Y à H:i') }}
                    </p>
                    @if ($message->isReplied())
                        <span class="font-[IBM_Plex_Mono] text-[10px] text-blue-400 bg-blue-400/10 px-2.5 py-0.5 rounded-full">
                            📩 Répondu
                        </span>
                    @else
                        <span class="font-[IBM_Plex_Mono] text-[10px] text-yellow-400 bg-yellow-400/10 px-2.5 py-0.5 rounded-full">
                            ⏳ En attente
                        </span>
                    @endif
                </div>
            </div>

            <div class="px-6 py-6">
                <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-4">
                    Sujet: <span class="text-[#F6F3EC]">{{ $message->subject }}</span>
                </p>
                <div class="bg-[#14151C] rounded-2xl p-6">
                    <p class="font-[Inter] text-sm text-[#F6F3EC] leading-relaxed whitespace-pre-wrap">{{ $message->message }}</p>
                </div>
            </div>
        </div>

        {{-- ===== RÉPONSE PRÉCÉDENTE ===== --}}
        @if ($message->isReplied())
            <div class="bg-green-400/5 border border-green-400/20 rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-green-400 text-lg">✓</span>
                    <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-green-400">
                        Réponse envoyée le {{ $message->replied_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div class="bg-[#14151C] rounded-2xl p-6 border-l-4 border-[#C9A24B]">
                    <p class="font-[Inter] text-sm text-[#F6F3EC] leading-relaxed whitespace-pre-wrap">{{ $message->reply }}</p>
                </div>
            </div>
        @endif

        {{-- ===== FORMULAIRE RÉPONSE ===== --}}
        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-white/10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#C9A24B]/15 flex items-center justify-center">
                    <span class="text-base">📩</span>
                </div>
                <div>
                    <h2 class="font-[Fraunces] text-xl">Répondre au client</h2>
                    <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">Envoi à: {{ $message->email }}</p>
                </div>
            </div>

            <div class="p-6">
                {{-- Infos envoi --}}
                <div class="space-y-2 mb-5">
                    <div class="flex items-center gap-4 bg-[#14151C] rounded-xl px-5 py-3">
                        <span class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] w-12 flex-shrink-0">De</span>
                        <span class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">
                            Boutique Paris &lt;{{ config('mail.from.address') }}&gt;
                        </span>
                    </div>
                    <div class="flex items-center gap-4 bg-[#14151C] rounded-xl px-5 py-3">
                        <span class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] w-12 flex-shrink-0">À</span>
                        <span class="font-[IBM_Plex_Mono] text-xs text-[#C9A24B]">
                            {{ $message->name }} &lt;{{ $message->email }}&gt;
                        </span>
                    </div>
                    <div class="flex items-center gap-4 bg-[#14151C] rounded-xl px-5 py-3">
                        <span class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] w-12 flex-shrink-0">Objet</span>
                        <span class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">Re: {{ $message->subject }}</span>
                    </div>
                </div>

                {{-- Textarea --}}
                <form action="{{ route('admin.support.reply', $message) }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Votre réponse *
                        </label>
                        <textarea name="reply" required rows="10"
                                  class="w-full bg-[#14151C] border border-white/15 px-5 py-4 focus:border-[#C9A24B] outline-none font-[Inter] text-sm rounded-2xl resize-none transition-colors leading-relaxed placeholder-[#9C9788]/40"
                                  placeholder="Écrivez votre réponse...">{{ old('reply', "Bonjour {$message->name},\n\nMerci de nous avoir contacté.\n\n\n\nCordialement,\nÉquipe Boutique Paris") }}</textarea>
                        @error('reply')
                            <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-sm font-bold uppercase tracking-widest rounded-2xl hover:bg-[#dab564] transition-colors">
                        📩 Envoyer la réponse
                    </button>
                </form>

                {{-- ===== BOUTON SUPPRIMER (FORM SÉPARÉ, EN DEHORS DU FORM REPLY) ===== --}}
                <div class="mt-4 pt-4 border-t border-white/8">
                    <form action="{{ route('admin.support.destroy', $message) }}" method="POST"
                          onsubmit="return confirm('Supprimer définitivement ce message ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full py-3 border border-red-400/20 text-red-400 font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-xl hover:bg-red-400/10 transition-colors">
                            🗑 Supprimer ce message
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection