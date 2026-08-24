@extends('layouts.shop')

@section('title', 'Paiement — Boutique Paris')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-16">

    <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4 text-center">
        Finalisation
    </p>

    <h1 class="font-[Fraunces] text-4xl text-center mb-2">
        Mode de paiement
    </h1>

    <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] text-center mb-12">
        Commande {{ $order->order_number }} —
        <span class="text-[#C9A24B] font-semibold">
            {{ number_format($order->total, 0, ',', ' ') }} DA
        </span>
    </p>


    {{-- ===== MESSAGE ERREUR ===== --}}
    @if (session('error'))
        <div class="bg-[#7A2E3A]/20 border border-[#7A2E3A]/40 text-[#e9b3bb] font-[IBM_Plex_Mono] text-xs px-4 py-3 rounded-xl mb-8 text-center">
            {{ session('error') }}
        </div>
    @endif


    {{-- ===== MÉTHODES DE PAIEMENT ===== --}}
    <div class="space-y-3" x-data="{ open: null }">

        @forelse ($activeMethods as $method)

            <div
                class="border border-white/10 rounded-xl overflow-hidden transition-all duration-200"
                :class="open === '{{ $method['key'] }}'
                    ? 'border-[#C9A24B]/50 bg-[#C9A24B]/4'
                    : 'hover:border-white/25'"
            >

                {{-- ===== HEADER ===== --}}
                <div
                    class="flex items-center justify-between px-6 py-5 cursor-pointer select-none"
                    @click="open = (open === '{{ $method['key'] }}'
                        ? null
                        : '{{ $method['key'] }}')"
                >

                    <div class="flex items-center gap-4">

                        {{-- ICON --}}
                        <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-xl flex-shrink-0">
                            {{ $method['icon'] }}
                        </div>

                        {{-- INFOS --}}
                        <div>

                            <p class="font-[Fraunces] text-lg leading-tight">
                                {{ $method['name'] }}
                            </p>

                            <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] uppercase tracking-wider">
                                {{ $method['subtitle'] }}
                            </p>

                            @if (!empty($method['badge']))
                                <p class="font-[IBM_Plex_Mono] text-[10px] mt-0.5 {{ $method['badge_color'] ?? 'text-[#9C9788]' }}">
                                    {{ $method['badge'] }}
                                </p>
                            @endif

                        </div>

                    </div>


                    {{-- ===== BOUTON CHOISIR ===== --}}
                    <div class="flex items-center gap-2 flex-shrink-0">

                        <span
                            x-show="open !== '{{ $method['key'] }}'"
                            class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest px-3 py-1.5 border border-[#C9A24B]/40 text-[#C9A24B] rounded-lg"
                        >
                            Choisir
                        </span>

                        <span
                            x-show="open === '{{ $method['key'] }}'"
                            class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]"
                        >
                            ▲
                        </span>

                    </div>

                </div>


                {{-- ===== BODY ===== --}}
                <div
                    x-show="open === '{{ $method['key'] }}'"
                    x-transition
                    class="border-t border-white/10 px-6 pb-6 pt-5"
                >


                    {{-- =====================================================
                         PAIEMENT AUTOMATIQUE / DIRECT
                    ====================================================== --}}
                    @if ($method['type'] === 'auto' || $method['type'] === 'direct')

                        <form
                            action="{{ $method['route'] }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors"
                            >
                                Confirmer — {{ $method['name'] }} →
                            </button>

                        </form>


                    {{-- =====================================================
                         PAIEMENT MANUEL
                    ====================================================== --}}
                    @elseif ($method['type'] === 'manual')


                        {{-- ===== BLOC INFOS BANCAIRES ===== --}}
                        <div class="bg-[#0E0F14] border border-[#C9A24B]/25 rounded-2xl overflow-hidden mb-4">

                            {{-- Header --}}
                            <div class="flex items-center justify-between px-5 py-4 border-b border-white/8">

                                <div class="flex items-center gap-3">

                                    <span class="text-2xl">
                                        {{ $method['icon'] }}
                                    </span>

                                    <div>

                                        <p class="font-[Fraunces] text-base leading-tight">
                                            {{ $method['name'] }}
                                        </p>

                                        <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                                            {{ $method['subtitle'] }}
                                        </p>

                                    </div>

                                </div>


                                <span class="font-[IBM_Plex_Mono] text-[10px] text-green-400 bg-green-400/10 border border-green-400/20 px-2 py-0.5 rounded-full">
                                    🔒 Sécurisé
                                </span>

                            </div>


                            {{-- Infos --}}
                            <div class="px-5 py-4 space-y-4">


                                {{-- Bénéficiaire --}}
                                <div>

                                    <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-[0.2em] text-[#9C9788] mb-1">
                                        Bénéficiaire
                                    </p>

                                    <p class="font-[Inter] text-sm font-semibold text-[#F6F3EC]">
                                        {{ $method['holder'] }}
                                    </p>

                                </div>


                                {{-- RIB --}}
                                <div>

                                    <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-[0.2em] text-[#9C9788] mb-1">
                                        {{ $method['rib_label'] ?? 'Numéro RIB / CCP' }}
                                    </p>

                                    <div class="flex items-center justify-between bg-[#1C1E27] border border-white/10 rounded-xl px-4 py-3">

                                        <span
                                            id="rib-{{ $method['key'] }}"
                                            class="font-[IBM_Plex_Mono] text-sm text-[#C9A24B] tracking-wider"
                                        >
                                            {{ $method['rib'] }}
                                        </span>

                                        <button
                                            type="button"
                                            onclick="copyText('rib-{{ $method['key'] }}', this)"
                                            class="flex items-center gap-1.5 font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] hover:text-[#C9A24B] transition-colors ml-3 flex-shrink-0"
                                        >
                                            <span>📋</span>
                                            <span>Copier</span>
                                        </button>

                                    </div>

                                </div>


                                {{-- Montant --}}
                                <div>

                                    <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-[0.2em] text-[#9C9788] mb-1">
                                        Montant à payer
                                    </p>

                                    <div class="flex items-center justify-between bg-[#1C1E27] border border-white/10 rounded-xl px-4 py-3">

                                        <span
                                            id="amount-{{ $method['key'] }}"
                                            class="font-[Fraunces] text-xl text-[#C9A24B] font-bold"
                                        >
                                            {{ number_format($order->total, 0, ',', ' ') }} DA
                                        </span>

                                        <button
                                            type="button"
                                            onclick="copyText('amount-{{ $method['key'] }}', this)"
                                            class="flex items-center gap-1.5 font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] hover:text-[#C9A24B] transition-colors ml-3 flex-shrink-0"
                                        >
                                            <span>📋</span>
                                            <span>Copier</span>
                                        </button>

                                    </div>

                                </div>


                                {{-- Référence commande --}}
                                <div>

                                    <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-[0.2em] text-[#9C9788] mb-1">
                                        Référence de commande
                                    </p>

                                    <div class="flex items-center justify-between bg-[#1C1E27] border border-white/10 rounded-xl px-4 py-3">

                                        <span
                                            id="ref-{{ $method['key'] }}"
                                            class="font-[IBM_Plex_Mono] text-sm text-[#F6F3EC]"
                                        >
                                            {{ $order->order_number }}
                                        </span>

                                        <button
                                            type="button"
                                            onclick="copyText('ref-{{ $method['key'] }}', this)"
                                            class="flex items-center gap-1.5 font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] hover:text-[#C9A24B] transition-colors ml-3 flex-shrink-0"
                                        >
                                            <span>📋</span>
                                            <span>Copier</span>
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =====================================================
                             FORMULAIRE PREUVE
                        ====================================================== --}}

                        <form
                            action="{{ route('payment.manual', $order) }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="space-y-4"
                            id="form-{{ $method['key'] }}"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="gateway"
                                value="{{ $method['key'] }}"
                            >


                            {{-- Dropzone --}}
                            <div>

                                <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-[0.2em] text-[#9C9788] mb-2">
                                    Téléverser votre preuve de paiement
                                </p>

                                <label
                                    for="file-{{ $method['key'] }}"
                                    class="block cursor-pointer group"
                                >

                                    <input
                                        type="file"
                                        id="file-{{ $method['key'] }}"
                                        name="proof_image"
                                        accept="image/*,.pdf"
                                        class="sr-only"
                                        onchange="handleFileSelect(this, '{{ $method['key'] }}')"
                                    >


                                    <div
                                        id="dropzone-{{ $method['key'] }}"
                                        class="border-2 border-dashed border-white/20 rounded-xl p-8 text-center transition-all duration-200 group-hover:border-[#C9A24B]/50 group-hover:bg-[#C9A24B]/3"
                                    >

                                        {{-- Etat normal --}}
                                        <div id="dropzone-idle-{{ $method['key'] }}">

                                            <div class="text-4xl mb-3">
                                                ☁️
                                            </div>

                                            <p class="font-[Inter] text-sm font-medium text-[#F6F3EC] mb-1">
                                                Déposez votre reçu ici
                                            </p>

                                            <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] mb-3">
                                                ou cliquez pour choisir
                                            </p>

                                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]/60">
                                                PDF • JPG • PNG — Max 10 MB
                                            </p>

                                        </div>


                                        {{-- Fichier sélectionné --}}
                                        <div
                                            id="dropzone-selected-{{ $method['key'] }}"
                                            class="hidden"
                                        >

                                            <div class="text-4xl mb-3">
                                                ✅
                                            </div>

                                            <p
                                                id="dropzone-filename-{{ $method['key'] }}"
                                                class="font-[IBM_Plex_Mono] text-xs text-[#C9A24B]"
                                            >
                                            </p>

                                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] mt-1">
                                                Cliquez pour changer
                                            </p>

                                        </div>

                                    </div>

                                </label>

                            </div>


                            {{-- Référence bancaire --}}
                            <div>

                                <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-[0.2em] text-[#9C9788] mb-2">
                                    Référence bancaire
                                    <span class="normal-case text-[#9C9788]/50">
                                        (optionnel)
                                    </span>
                                </p>

                                <input
                                    type="text"
                                    name="proof_notes"
                                    placeholder="Transaction ID ou N° de virement..."
                                    class="w-full bg-[#0E0F14] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-[11px] rounded-xl transition-colors placeholder-[#9C9788]/40"
                                >

                            </div>


                            {{-- Confirmer --}}
                            <button
                                type="submit"
                                class="w-full py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.2em] rounded-xl hover:bg-[#dab564] transition-colors font-bold flex items-center justify-center gap-2"
                            >

                                <span>✓</span>

                                <span>
                                    Confirmer le paiement
                                </span>

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        @empty

            {{-- Aucun paiement --}}
            <div class="border border-white/10 rounded-xl p-8 text-center">

                <p class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
                    Aucun mode de paiement configuré.
                </p>

            </div>

        @endforelse

    </div>


    {{-- ===== RETOUR ===== --}}
    <p class="mt-10 text-center">

        <a
            href="{{ route('checkout.index') }}"
            class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] hover:text-[#C9A24B] transition-colors"
        >
            ← Retour à la commande
        </a>

    </p>

</div>


<script>

    // =========================================================
    // COPIER TEXTE
    // =========================================================

    function copyText(elementId, btn) {

        const element = document.getElementById(elementId);

        if (!element) {
            return;
        }

        const text = element.innerText.trim();

        navigator.clipboard.writeText(text).then(() => {

            const original = btn.innerHTML;

            btn.innerHTML = '<span>✓</span> <span>Copié !</span>';

            btn.classList.add('text-green-400');

            setTimeout(() => {

                btn.innerHTML = original;

                btn.classList.remove('text-green-400');

            }, 2000);

        });

    }


    // =========================================================
    // DROPZONE / FICHIER
    // =========================================================

    function handleFileSelect(input, key) {

        const file = input.files[0];

        if (!file) {
            return;
        }

        const idle = document.getElementById(
            'dropzone-idle-' + key
        );

        const selected = document.getElementById(
            'dropzone-selected-' + key
        );

        const filename = document.getElementById(
            'dropzone-filename-' + key
        );

        if (!idle || !selected || !filename) {
            return;
        }

        idle.classList.add('hidden');

        selected.classList.remove('hidden');

        filename.textContent = file.name;

    }

</script>

@endsection