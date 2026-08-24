@extends('layouts.shop')

@section('title', 'Support — Boutique Paris')

@section('content')

    <div class="max-w-3xl mx-auto px-6 py-16">

        {{-- Header --}}
        <div class="text-center mb-12">
            <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4">
                Aide & Contact
            </p>
            <h1 class="font-[Fraunces] text-4xl md:text-5xl mb-4">Support Client</h1>
            <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] max-w-md mx-auto leading-relaxed">
                Une question ? Un problème avec votre commande ?<br>
                Écrivez-nous — nous répondons dans les 24h.
            </p>
        </div>

        @if (session('success'))
            <div class="bg-[#C9A24B]/10 border border-[#C9A24B]/30 rounded-2xl p-8 text-center mb-8">
                <div class="text-5xl mb-4">✅</div>
                <p class="font-[Fraunces] text-2xl text-[#C9A24B] mb-2">Message envoyé !</p>
                <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788]">
                    Nous avons bien reçu votre message. Notre équipe vous répondra par e-mail dans les plus brefs délais.
                </p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-[#7A2E3A]/20 border border-[#7A2E3A]/40 text-[#e9b3bb] font-[IBM_Plex_Mono] text-xs px-4 py-3 rounded-xl mb-8 text-center">
                {{ session('error') }}
            </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">

{{-- ===== FORMULAIRE ===== --}}
<div class="lg:col-span-1">
                <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-8">
                    <h2 class="font-[Fraunces] text-xl mb-6">Envoyez-nous un message</h2>

                    <form action="{{ route('support.send') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                                    Nom complet *
                                </label>
                                <input type="text" name="name"
                                       value="{{ old('name', auth()->user()->name ?? '') }}"
                                       required
                                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl transition-colors">
                                @error('name') <p class="text-[#e9b3bb] text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                                    E-mail *
                                </label>
                                <input type="email" name="email"
                                       value="{{ old('email', auth()->user()->email ?? '') }}"
                                       required
                                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl transition-colors">
                                @error('email') <p class="text-[#e9b3bb] text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                                Sujet *
                            </label>
                            <select name="subject" required
                                    class="w-full bg-[#14151C] border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl text-[#F6F3EC]">
                                <option value="">— Choisir un sujet —</option>
                                @foreach ([
                                    'Problème de commande',
                                    'Retour / Remboursement',
                                    'Problème de paiement',
                                    'Question sur un produit',
                                    'Livraison',
                                    'Compte client',
                                    'Autre',
                                ] as $opt)
                                    <option value="{{ $opt }}" {{ old('subject') == $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject') <p class="text-[#e9b3bb] text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                       
    <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
        Numéro de commande (optionnel)
    </label>

    <input
        type="text"
        name="order_number"
        value="{{ old('order_number') }}"
        placeholder="CMD-XXXXXXXX-XXXXX"
        class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl placeholder-[#9C9788]/40 transition-colors">
</div>

                        <div>
                            <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                                Message *
                            </label>
                            <textarea name="message" required rows="5"
                                      placeholder="Décrivez votre problème en détail..."
                                      class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl resize-none placeholder-[#9C9788]/40 transition-colors">{{ old('message') }}</textarea>
                            @error('message') <p class="text-[#e9b3bb] text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-sm font-bold uppercase tracking-widest rounded-xl hover:bg-[#dab564] transition-colors">
                            Envoyer le message →
                        </button>
                    </form>
                </div>
            </div>

            {{-- ===== CONTACT INFO ===== --}}
            <div class="space-y-4">

                <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-6">
                    <h3 class="font-[Fraunces] text-lg mb-5">Nous contacter</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-xl mt-0.5">📧</span>
                            <div>
                                <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] mb-0.5">E-mail</p>
                                <p class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">support@boutiqueparis.dz</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl mt-0.5">📞</span>
                            <div>
                                <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] mb-0.5">Téléphone</p>
                                <p class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">0792026620</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl mt-0.5">🕐</span>
                            <div>
                                <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] mb-0.5">Horaires</p>
                                <p class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">Dim — Jeu</p>
                                <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">9h00 — 17h00</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl mt-0.5">📅</span>
                            <div>
                                <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] mb-0.5">Aujourd'hui</p>
                                <p class="font-[IBM_Plex_Mono] text-xs text-[#F6F3EC]">
                                    {{ now()->locale('fr')->isoFormat('dddd D MMMM') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#C9A24B]/8 border border-[#C9A24B]/20 rounded-2xl p-6">
                    <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#C9A24B] mb-2">
                        ⚡ Réponse rapide
                    </p>
                    <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] leading-relaxed">
                        Nous répondons à tous les messages sous <strong class="text-[#F6F3EC]">24 heures</strong> ouvrables.
                    </p>
                </div>

                @php $waNumber = \App\Models\Setting::get('whatsapp_number'); @endphp
                @if ($waNumber)
                    <a href="https://wa.me/{{ $waNumber }}"
                       target="_blank"
                       class="flex items-center gap-3 bg-[#25D366]/10 border border-[#25D366]/30 rounded-2xl p-5 hover:bg-[#25D366]/15 transition-colors">
                        <span class="text-2xl">💬</span>
                        <div>
                            <p class="font-[IBM_Plex_Mono] text-xs font-bold text-[#25D366]">WhatsApp</p>
                            <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">Réponse immédiate</p>
                        </div>
                    </a>
                @endif

            </div>
        </div>
    </div>

@endsection