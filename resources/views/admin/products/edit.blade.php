@extends('admin.layouts.app')

@section('title', 'Modifier: ' . $product->name)

@section('content')

<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.products.index') }}"
       class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
        ← Retour
    </a>

    <div>
        <h1 class="font-[Fraunces] text-3xl">Modifier le produit</h1>
        <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] mt-0.5">
            {{ $product->name }}
        </p>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- =========================================================
             COLONNE PRINCIPALE
        ========================================================== --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- =====================================================
                 INFORMATIONS
            ====================================================== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">

                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#C9A24B]/15 flex items-center justify-center">
                        <span class="text-sm">📝</span>
                    </div>

                    <h2 class="font-[Fraunces] text-lg">
                        Informations générales
                    </h2>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Nom --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Nom du produit
                            <span class="text-red-400">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $product->name) }}"
                            required
                            class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[Inter] text-sm rounded-xl transition-colors text-[#F6F3EC]"
                        >

                        @error('name')
                            <p class="text-red-400 text-[10px] mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[Inter] text-sm rounded-xl resize-none transition-colors text-[#F6F3EC] placeholder-[#9C9788]/40"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>

                </div>
            </div>


            {{-- =====================================================
                 PRIX
            ====================================================== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">

                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-green-400/15 flex items-center justify-center">
                        <span class="text-sm">💰</span>
                    </div>

                    <h2 class="font-[Fraunces] text-lg">
                        Prix
                    </h2>

                </div>

                <div class="p-6">

                    <div class="grid grid-cols-3 gap-3">

                        {{-- =========================
                             PRIX ACHAT
                        ========================== --}}
                        <div>

                            <label class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] block mb-1.5">
                                Prix d'achat
                            </label>

                            <div class="relative">

                                <input
                                    type="number"
                                    name="cost_price"
                                    value="{{ old('cost_price', $product->cost_price) }}"
                                    min="0"
                                    step="1"
                                    inputmode="numeric"
                                    class="w-full bg-[#14151C] border border-white/15 pl-3 pr-8 py-2.5 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg transition-colors text-[#F6F3EC]"
                                    placeholder="0"
                                >

                                <span class="absolute right-2.5 top-2.5 font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">
                                    DA
                                </span>

                            </div>

                            <p class="font-[IBM_Plex_Mono] text-[8px] text-[#9C9788]/60 mt-1">
                                Ce que vous payez
                            </p>

                        </div>


                        {{-- =========================
                             PRIX VENTE
                        ========================== --}}
                        <div>

                            <label class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] block mb-1.5">
                                Prix de vente
                                <span class="text-red-400">*</span>
                            </label>

                            <div class="relative">

                                <input
                                    type="number"
                                    name="price"
                                    value="{{ old('price', $product->price) }}"
                                    required
                                    min="0"
                                    step="1"
                                    inputmode="numeric"
                                    class="w-full bg-[#14151C] border border-[#C9A24B]/30 pl-3 pr-8 py-2.5 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg transition-colors text-[#C9A24B] font-bold"
                                    placeholder="0"
                                >

                                <span class="absolute right-2.5 top-2.5 font-[IBM_Plex_Mono] text-[9px] text-[#C9A24B]">
                                    DA
                                </span>

                            </div>

                            <p class="font-[IBM_Plex_Mono] text-[8px] text-[#9C9788]/60 mt-1">
                                Ce que paie le client
                            </p>

                        </div>


                        {{-- =========================
                             PRIX PROMO
                        ========================== --}}
                        <div>

                            <label class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] block mb-1.5">
                                Prix promo
                            </label>

                            <div class="relative">

                                <input
                                    type="number"
                                    name="sale_price"
                                    value="{{ old('sale_price', $product->sale_price) }}"
                                    min="0"
                                    step="1"
                                    inputmode="numeric"
                                    class="w-full bg-[#14151C] border border-white/15 pl-3 pr-8 py-2.5 focus:border-orange-400/50 outline-none font-[IBM_Plex_Mono] text-xs rounded-lg transition-colors text-orange-400"
                                    placeholder="0"
                                >

                                <span class="absolute right-2.5 top-2.5 font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">
                                    DA
                                </span>

                            </div>

                            <p class="font-[IBM_Plex_Mono] text-[8px] text-[#9C9788]/60 mt-1">
                                Prix soldé (optionnel)
                            </p>

                        </div>

                    </div>


                    {{-- MARGE --}}
                    <div id="margin-display" class="mt-3 hidden">

                        <div class="bg-green-400/5 border border-green-400/20 rounded-lg px-4 py-2 flex items-center gap-3">

                            <span class="text-sm">📈</span>

                            <p
                                class="font-[IBM_Plex_Mono] text-[10px] text-green-400"
                                id="margin-text">
                            </p>

                        </div>

                    </div>

                </div>
            </div>


            {{-- =====================================================
                 IMAGE
            ====================================================== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">

                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-blue-400/15 flex items-center justify-center">
                        <span class="text-sm">🖼️</span>
                    </div>

                    <h2 class="font-[Fraunces] text-lg">
                        Image principale
                    </h2>

                </div>

                <div class="p-6">

                    <div class="flex gap-6 items-start">

                        {{-- Image actuelle --}}
                        @if ($product->image)

                            <div class="flex-shrink-0">

                                <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] mb-2">
                                    Actuelle
                                </p>

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    class="w-28 h-32 object-cover rounded-xl border border-white/10"
                                >

                            </div>

                        @endif


                        {{-- Nouvelle image --}}
                        <div class="flex-1">

                            <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788] mb-2">
                                {{ $product->image ? "Changer l'image" : 'Ajouter une image' }}
                            </p>

                            <label class="block cursor-pointer group">

                                <input
                                    type="file"
                                    name="image"
                                    accept="image/*"
                                    class="hidden"
                                    onchange="previewImage(this)"
                                >

                                <div class="border-2 border-dashed border-white/15 rounded-xl p-6 text-center group-hover:border-[#C9A24B]/40 group-hover:bg-[#C9A24B]/3 transition-all">

                                    <div id="preview-placeholder">

                                        <p class="font-[Inter] text-sm text-[#9C9788] mb-1">
                                            Cliquez pour choisir
                                        </p>

                                        <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]/60">
                                            JPG • PNG • WEBP — Max 2MB
                                        </p>

                                    </div>

                                    <img
                                        id="preview-img"
                                        src=""
                                        alt=""
                                        class="hidden max-h-32 mx-auto rounded-lg"
                                    >

                                </div>

                            </label>

                        </div>

                    </div>

                </div>
            </div>


            {{-- =====================================================
                 VARIANTES & STOCK
            ====================================================== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">

                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div class="w-8 h-8 rounded-lg bg-purple-400/15 flex items-center justify-center">
                            <span class="text-sm">🎨</span>
                        </div>

                        <div>

                            <h2 class="font-[Fraunces] text-lg">
                                Variantes & Stock
                            </h2>

                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">
                                Modifiez les stocks directement
                            </p>

                        </div>

                    </div>

                    <button
                        type="button"
                        onclick="addVariant()"
                        class="flex items-center gap-2 px-4 py-2 bg-[#C9A24B]/10 border border-[#C9A24B]/30 text-[#C9A24B] font-[IBM_Plex_Mono] text-[10px] uppercase tracking-wider rounded-lg hover:bg-[#C9A24B]/20 transition-colors"
                    >
                        + Ajouter
                    </button>

                </div>


                <div class="p-6 space-y-4">

                    <div
                        id="variants-container"
                        class="space-y-2"
                    >

                        @foreach ($product->variants as $i => $variant)

                            <div class="variant-row bg-[#14151C] border border-white/8 rounded-xl px-4 py-3 relative">

                                <input
                                    type="hidden"
                                    name="variants[{{ $i }}][id]"
                                    value="{{ $variant->id }}"
                                >


                                {{-- Taille + couleur --}}
                                <div class="grid grid-cols-2 gap-3 mb-2.5">

                                    <div>

                                        <input
                                            type="text"
                                            name="variants[{{ $i }}][size]"
                                            value="{{ $variant->size }}"
                                            placeholder="Taille (ex: S, M, L, 42...)"
                                            class="variant-size w-full bg-transparent border border-white/15 px-3 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] placeholder-[#9C9788]/50"
                                        >

                                    </div>


                                    <div class="flex gap-2">

                                        <input
                                            type="text"
                                            name="variants[{{ $i }}][color]"
                                            value="{{ $variant->color }}"
                                            placeholder="Couleur (ex: Noir, Rouge...)"
                                            class="variant-color flex-1 bg-transparent border border-white/15 px-3 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] placeholder-[#9C9788]/50"
                                        >

                                        <input
                                            type="color"
                                            name="variants[{{ $i }}][color_hex]"
                                            value="{{ $variant->color_hex ?? '#1a1a1a' }}"
                                            class="w-9 h-9 rounded-lg border border-white/15 bg-transparent cursor-pointer p-0.5 flex-shrink-0"
                                        >

                                    </div>

                                </div>


                                {{-- Stock + extra prix --}}
                                <div class="grid grid-cols-2 gap-3">

                                    <div>

                                        <input
                                            type="number"
                                            name="variants[{{ $i }}][stock]"
                                            value="{{ $variant->stock }}"
                                            min="0"
                                            step="1"
                                            inputmode="numeric"
                                            placeholder="Quantité en stock"
                                            oninput="updateSummary()"
                                            class="variant-stock w-full bg-transparent border font-[IBM_Plex_Mono] text-xs rounded-lg px-3 py-2 focus:border-[#C9A24B] outline-none placeholder-[#9C9788]/50
                                            {{ $variant->stock === 0
                                                ? 'border-red-400/40 text-red-400'
                                                : ($variant->stock <= 5
                                                    ? 'border-yellow-400/40 text-yellow-400'
                                                    : 'border-white/15 text-green-400') }}"
                                        >

                                    </div>


                                    <div>

                                        <input
                                            type="number"
                                            name="variants[{{ $i }}][extra_price]"
                                            value="{{ $variant->extra_price ?? 0 }}"
                                            min="0"
                                            step="1"
                                            inputmode="numeric"
                                            placeholder="Prix supplémentaire (ex: +200)"
                                            class="variant-extra w-full bg-transparent border border-white/15 px-3 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] placeholder-[#9C9788]/50"
                                        >

                                    </div>

                                </div>


                                {{-- Supprimer --}}
                                <button
                                    type="button"
                                    onclick="removeVariant(this)"
                                    class="absolute top-3 right-3 text-[#9C9788] hover:text-red-400 transition-colors text-xs w-5 h-5 flex items-center justify-center rounded hover:bg-red-400/10"
                                >
                                    ✕
                                </button>

                            </div>

                        @endforeach

                    </div>


                    {{-- =================================================
                         SUMMARY
                    ================================================== --}}
                    <div class="flex items-center gap-6 bg-[#14151C] rounded-xl px-4 py-3">

                        <div class="text-center">

                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] uppercase">
                                Variantes
                            </p>

                            <p
                                id="summary-variants"
                                class="font-[Fraunces] text-lg text-[#F6F3EC]"
                            >
                                {{ $product->variants->count() }}
                            </p>

                        </div>


                        <div class="text-center">

                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] uppercase">
                                Stock total
                            </p>

                            <p
                                id="summary-stock"
                                class="font-[Fraunces] text-lg text-[#C9A24B]"
                            >
                                {{ $product->variants->sum('stock') }}
                            </p>

                        </div>


                        <div class="flex-1">

                            <div class="flex justify-between mb-1">

                                <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">
                                    Niveau
                                </p>

                                <p
                                    id="stock-status-label"
                                    class="font-[IBM_Plex_Mono] text-[9px] text-green-400"
                                >
                                    Bon
                                </p>

                            </div>

                            <div class="w-full bg-white/10 rounded-full h-1.5">

                                <div
                                    id="stock-bar"
                                    class="h-1.5 rounded-full bg-green-400 transition-all"
                                    style="width:80%"
                                ></div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>


        {{-- =========================================================
             COLONNE DROITE
        ========================================================== --}}
        <div class="space-y-6">


            {{-- =====================================================
                 CATEGORIE
            ====================================================== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">

                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-yellow-400/15 flex items-center justify-center">
                        <span class="text-sm">📁</span>
                    </div>

                    <h2 class="font-[Fraunces] text-lg">
                        Catégorie
                    </h2>

                </div>


                <div class="p-6">

                    @php
                        $mainCategories = \App\Models\Category::whereNull('parent_id')
                            ->with('children')
                            ->orderBy('order')
                            ->get();
                    @endphp

                    <select
                        name="category_id"
                        required
                        class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl text-[#F6F3EC]"
                    >

                        <option value="">
                            — Choisir —
                        </option>

                        @foreach ($mainCategories as $main)

                            <optgroup
                                label="── {{ $main->name }} ──"
                                style="color:#C9A24B;background:#14151C;font-size:11px;"
                            >

                                @foreach ($main->children as $child)

                                    <option
                                        value="{{ $child->id }}"
                                        {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}
                                        style="color:#F6F3EC;background:#14151C;"
                                    >
                                        {{ $child->name }}
                                    </option>

                                @endforeach

                            </optgroup>

                        @endforeach

                    </select>


                    @error('category_id')

                        <p class="text-red-400 text-[10px] mt-2">
                            {{ $message }}
                        </p>

                    @enderror

                </div>
            </div>


            {{-- =====================================================
                 STATUT
            ====================================================== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">

                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">

                    <div class="w-8 h-8 rounded-lg bg-[#C9A24B]/15 flex items-center justify-center">
                        <span class="text-sm">⚡</span>
                    </div>

                    <h2 class="font-[Fraunces] text-lg">
                        Statut
                    </h2>

                </div>


                <div class="p-6 space-y-3">


                    {{-- Actif --}}
                    <div class="flex items-center justify-between p-4 bg-[#14151C] rounded-xl border border-white/8 hover:border-white/15 transition-colors">

                        <div>

                            <p class="font-[Inter] text-sm font-medium text-[#F6F3EC]">
                                Produit actif
                            </p>

                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] mt-0.5">
                                Visible sur le site
                            </p>

                        </div>


                        <button
                            type="button"
                            onclick="toggleStatus('is_active')"
                            id="btn-is_active"
                            class="relative w-12 h-6 rounded-full transition-all duration-300 focus:outline-none flex-shrink-0"
                            style="background: {{ old('is_active', $product->is_active) ? '#22c55e' : 'rgba(255,255,255,0.15)' }}"
                        >

                            <span
                                id="dot-is_active"
                                class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all duration-300"
                                style="left: {{ old('is_active', $product->is_active) ? '26px' : '2px' }}"
                            ></span>

                        </button>


                        <input
                            type="hidden"
                            name="is_active"
                            id="input-is_active"
                            value="{{ old('is_active', $product->is_active) ? '1' : '0' }}"
                        >

                    </div>


                    {{-- Featured --}}
                    <div class="flex items-center justify-between p-4 bg-[#14151C] rounded-xl border border-white/8 hover:border-white/15 transition-colors">

                        <div>

                            <p class="font-[Inter] text-sm font-medium text-[#F6F3EC]">
                                Produit vedette
                            </p>

                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] mt-0.5">
                                Affiché en page d'accueil
                            </p>

                        </div>


                        <button
                            type="button"
                            onclick="toggleStatus('is_featured')"
                            id="btn-is_featured"
                            class="relative w-12 h-6 rounded-full transition-all duration-300 focus:outline-none flex-shrink-0"
                            style="background: {{ old('is_featured', $product->is_featured) ? '#C9A24B' : 'rgba(255,255,255,0.15)' }}"
                        >

                            <span
                                id="dot-is_featured"
                                class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all duration-300"
                                style="left: {{ old('is_featured', $product->is_featured) ? '26px' : '2px' }}"
                            ></span>

                        </button>


                        <input
                            type="hidden"
                            name="is_featured"
                            id="input-is_featured"
                            value="{{ old('is_featured', $product->is_featured) ? '1' : '0' }}"
                        >

                    </div>

                </div>
            </div>


            {{-- =====================================================
                 SUBMIT
            ====================================================== --}}
            <div class="space-y-3">

                <button
                    type="submit"
                    class="w-full py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-sm font-bold uppercase tracking-widest rounded-2xl hover:bg-[#dab564] transition-colors"
                >
                    ✓ Enregistrer les modifications
                </button>


                <a
                    href="{{ route('admin.products.index') }}"
                    class="block w-full py-3 text-center border border-white/10 text-[#9C9788] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-2xl hover:border-white/25 transition-colors"
                >
                    Annuler
                </a>

            </div>


            {{-- =====================================================
                 INFO PRODUIT
            ====================================================== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-5 space-y-3">

                <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788]">
                    Informations
                </p>


                <div class="space-y-2">

                    <div class="flex justify-between font-[IBM_Plex_Mono] text-xs">

                        <span class="text-[#9C9788]">
                            Créé le
                        </span>

                        <span class="text-[#F6F3EC]">
                            {{ $product->created_at->format('d/m/Y') }}
                        </span>

                    </div>


                    <div class="flex justify-between font-[IBM_Plex_Mono] text-xs">

                        <span class="text-[#9C9788]">
                            Modifié le
                        </span>

                        <span class="text-[#F6F3EC]">
                            {{ $product->updated_at->format('d/m/Y H:i') }}
                        </span>

                    </div>


                    <div class="flex justify-between font-[IBM_Plex_Mono] text-xs">

                        <span class="text-[#9C9788]">
                            Stock total
                        </span>

                        <span
                            id="summary-stock-sidebar"
                            class="text-[#C9A24B] font-bold"
                        >
                            {{ $product->variants->sum('stock') }}
                        </span>

                    </div>

                </div>


                <a
                    href="{{ route('shop.product', $product->slug) }}"
                    target="_blank"
                    class="block w-full text-center font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] hover:text-[#C9A24B] transition-colors mt-2"
                >
                    🔗 Voir sur le site →
                </a>

            </div>

        </div>

    </div>

</form>


{{-- =============================================================
     JAVASCRIPT
============================================================== --}}
<script>

let variantIndex = {{ $product->variants->count() }};


/* =============================================================
   TOGGLE STATUT
============================================================= */

function toggleStatus(name) {

    const btn = document.getElementById('btn-' + name);
    const dot = document.getElementById('dot-' + name);
    const input = document.getElementById('input-' + name);

    const isOn = input.value === '1';

    if (isOn) {

        input.value = '0';

        dot.style.left = '2px';

        btn.style.background = 'rgba(255,255,255,0.15)';

    } else {

        input.value = '1';

        dot.style.left = '26px';

        btn.style.background =
            name === 'is_active'
                ? '#22c55e'
                : '#C9A24B';
    }
}


/* =============================================================
   CALCUL MARGE
============================================================= */

function calcMarge() {

    const cost =
        parseFloat(
            document.querySelector('[name="cost_price"]')?.value
        ) || 0;

    const price =
        parseFloat(
            document.querySelector('[name="price"]')?.value
        ) || 0;

    const display =
        document.getElementById('margin-display');

    const text =
        document.getElementById('margin-text');


    if (cost > 0 && price > 0) {

        const profit = price - cost;

        const margin =
            Math.round((profit / price) * 100);


        display.classList.remove('hidden');


        text.textContent =
            `Bénéfice: ${profit >= 0 ? '+' : ''}${profit.toLocaleString()} DA — Marge: ${margin}%`;

    } else {

        display.classList.add('hidden');

    }
}


/* =============================================================
   LISTENERS PRIX
============================================================= */

document
    .querySelector('[name="cost_price"]')
    ?.addEventListener('input', calcMarge);

document
    .querySelector('[name="price"]')
    ?.addEventListener('input', calcMarge);

calcMarge();


/* =============================================================
   AJOUTER VARIANTE
============================================================= */

function addVariant() {

    const idx = variantIndex;

    const div =
        document.createElement('div');

    div.className =
        'variant-row bg-[#14151C] border border-white/8 rounded-xl px-4 py-3 relative';


    div.innerHTML = `

        <div class="grid grid-cols-2 gap-3 mb-2.5">

            <input
                type="text"
                name="variants[${idx}][size]"
                placeholder="Taille (ex: S, M, L, 42...)"
                class="variant-size w-full bg-transparent border border-white/15 px-3 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] placeholder-[#9C9788]/50"
            >


            <div class="flex gap-2">

                <input
                    type="text"
                    name="variants[${idx}][color]"
                    placeholder="Couleur (ex: Noir, Rouge...)"
                    class="variant-color flex-1 bg-transparent border border-white/15 px-3 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] placeholder-[#9C9788]/50"
                >

                <input
                    type="color"
                    name="variants[${idx}][color_hex]"
                    value="#1a1a1a"
                    class="w-9 h-9 rounded-lg border border-white/15 bg-transparent cursor-pointer p-0.5 flex-shrink-0"
                >

            </div>

        </div>


        <div class="grid grid-cols-2 gap-3">

            <input
                type="number"
                name="variants[${idx}][stock]"
                value="10"
                min="0"
                step="1"
                inputmode="numeric"
                placeholder="Quantité en stock"
                oninput="updateSummary()"
                class="variant-stock w-full bg-transparent border border-white/15 px-3 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-green-400 placeholder-[#9C9788]/50"
            >


            <input
                type="number"
                name="variants[${idx}][extra_price]"
                value="0"
                min="0"
                step="1"
                inputmode="numeric"
                placeholder="Prix supplémentaire (ex: +200)"
                class="variant-extra w-full bg-transparent border border-white/15 px-3 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] placeholder-[#9C9788]/50"
            >

        </div>


        <button
            type="button"
            onclick="removeVariant(this)"
            class="absolute top-3 right-3 text-[#9C9788] hover:text-red-400 transition-colors text-xs w-5 h-5 flex items-center justify-center rounded hover:bg-red-400/10"
        >
            ✕
        </button>
    `;


    document
        .getElementById('variants-container')
        .appendChild(div);


    variantIndex++;

    updateSummary();
}


/* =============================================================
   SUPPRIMER VARIANTE
============================================================= */

function removeVariant(btn) {

    const rows =
        document.querySelectorAll('.variant-row');


    if (rows.length > 1) {

        btn
            .closest('.variant-row')
            .remove();

        updateSummary();
    }
}


/* =============================================================
   MISE À JOUR STOCK
============================================================= */

function updateSummary() {

    const rows =
        document.querySelectorAll('.variant-row');

    let total = 0;


    rows.forEach(row => {

        const stock =
            row.querySelector('.variant-stock');

        if (stock) {

            total +=
                parseInt(stock.value) || 0;
        }

    });


    document.getElementById('summary-variants').textContent =
        rows.length;


    document.getElementById('summary-stock').textContent =
        total;


    const sidebar =
        document.getElementById('summary-stock-sidebar');

    if (sidebar) {

        sidebar.textContent =
            total;
    }


    const bar =
        document.getElementById('stock-bar');

    const label =
        document.getElementById('stock-status-label');


    const avg =
        rows.length > 0
            ? total / rows.length
            : 0;


    const pct =
        rows.length > 0
            ? Math.min(
                (total / (rows.length * 20)) * 100,
                100
            )
            : 0;


    bar.style.width =
        pct + '%';


    if (total === 0) {

        bar.className =
            'h-1.5 rounded-full bg-red-400 transition-all';

        label.className =
            'font-[IBM_Plex_Mono] text-[9px] text-red-400';

        label.textContent =
            'Rupture';


    } else if (avg <= 5) {

        bar.className =
            'h-1.5 rounded-full bg-yellow-400 transition-all';

        label.className =
            'font-[IBM_Plex_Mono] text-[9px] text-yellow-400';

        label.textContent =
            'Bas';


    } else {

        bar.className =
            'h-1.5 rounded-full bg-green-400 transition-all';

        label.className =
            'font-[IBM_Plex_Mono] text-[9px] text-green-400';

        label.textContent =
            'Bon';
    }
}


/* =============================================================
   PREVIEW IMAGE
============================================================= */

function previewImage(input) {

    if (input.files && input.files[0]) {

        const reader =
            new FileReader();


        reader.onload = function(e) {

            document
                .getElementById('preview-placeholder')
                .classList
                .add('hidden');


            const img =
                document.getElementById('preview-img');


            img.src =
                e.target.result;


            img.classList.remove('hidden');
        };


        reader.readAsDataURL(input.files[0]);
    }
}


/* =============================================================
   INITIALISATION
============================================================= */

updateSummary();

</script>

@endsection