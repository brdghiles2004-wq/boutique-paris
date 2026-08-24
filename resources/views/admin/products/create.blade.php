@extends('admin.layouts.app')
@section('title', 'Ajouter un produit')

@section('content')

<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.products.index') }}"
       class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
        ← Retour
    </a>
    <div>
        <h1 class="font-[Fraunces] text-3xl">Ajouter un produit</h1>
        <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] mt-0.5">Remplissez tous les champs requis</p>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
      id="product-form">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ===== COLONNE GAUCHE (2/3) ===== --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Informations --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#C9A24B]/15 flex items-center justify-center">
                        <span class="text-sm">📝</span>
                    </div>
                    <h2 class="font-[Fraunces] text-lg">Informations générales</h2>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Nom du produit <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[Inter] text-sm rounded-xl transition-colors"
                               placeholder="Ex: T-shirt Premium Noir">
                        @error('name') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="4"
                                  class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[Inter] text-sm rounded-xl resize-none transition-colors placeholder-[#9C9788]/40"
                                  placeholder="Décrivez le produit, ses matières, ses caractéristiques...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

        {{-- Prix --}}
<div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-green-400/15 flex items-center justify-center">
            <span class="text-sm">💰</span>
        </div>
        <h2 class="font-[Fraunces] text-lg">Prix</h2>
    </div>

    <div class="p-6 space-y-5">

        {{-- Prix normal + Prix promo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Prix normal --}}
            <div>
                <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                    Prix normal (DA) <span class="text-red-400">*</span>
                </label>

                <div class="relative">
                    <input
                        type="number"
                        name="price"
                        value="{{ old('price') }}"
                        required
                        min="0"
                        step="1"
                        inputmode="numeric"
                        class="w-full bg-[#14151C] border border-white/15 px-4 py-3 pr-12 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-base rounded-xl transition-colors"
                        placeholder="Ex: 4569"
                    >

                    <span class="absolute right-4 top-3.5 font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
                        DA
                    </span>
                </div>

                @error('price')
                    <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prix promo --}}
            <div>
                <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                    Prix promo (DA)
                    <span class="text-[#9C9788]/60 normal-case">(optionnel)</span>
                </label>

                <div class="relative">
                    <input
                        type="number"
                        name="sale_price"
                        value="{{ old('sale_price') }}"
                        min="0"
                        step="1"
                        inputmode="numeric"
                        class="w-full bg-[#14151C] border border-white/15 px-4 py-3 pr-12 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-base rounded-xl transition-colors"
                        placeholder="Ex: 4399"
                    >

                    <span class="absolute right-4 top-3.5 font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
                        DA
                    </span>
                </div>

                @error('sale_price')
                    <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Prix d'achat --}}
        <div>
            <label class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] block mb-2">
                Prix d'achat (DA)
                <span class="text-[#9C9788]/50 normal-case">(pour calcul bénéfice)</span>
            </label>

            <div class="relative">
                <input
                    type="number"
                    name="cost_price"
                    value="{{ old('cost_price') }}"
                    min="0"
                    step="1"
                    inputmode="numeric"
                    class="w-full bg-[#14151C] border border-white/15 px-4 py-3 pr-12 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-base rounded-xl transition-colors"
                    placeholder="Ex: 3127"
                >

                <span class="absolute right-4 top-3.5 font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
                    DA
                </span>
            </div>

            @error('cost_price')
                <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>

            {{-- Image --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-400/15 flex items-center justify-center">
                        <span class="text-sm">🖼️</span>
                    </div>
                    <h2 class="font-[Fraunces] text-lg">Image principale</h2>
                </div>
                <div class="p-6">
                    <label class="block cursor-pointer group">
                        <input type="file" name="image" accept="image/*" class="hidden"
                               onchange="previewImage(this)">
                        <div id="drop-zone"
                             class="border-2 border-dashed border-white/20 rounded-2xl p-12 text-center
                                    group-hover:border-[#C9A24B]/50 group-hover:bg-[#C9A24B]/3 transition-all duration-200">
                            <div id="preview-placeholder">
                                <div class="text-5xl mb-4">📸</div>
                                <p class="font-[Inter] text-sm font-medium text-[#F6F3EC] mb-1">
                                    Glissez une image ou cliquez
                                </p>
                                <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                                    JPG • PNG • WEBP — Max 2MB
                                </p>
                            </div>
                            <div id="preview-done" class="hidden">
                                <img id="preview-img" src="" alt=""
                                     class="max-h-48 mx-auto rounded-xl object-contain mb-3">
                                <p class="font-[IBM_Plex_Mono] text-[10px] text-green-400">
                                    ✓ Image sélectionnée — cliquez pour changer
                                </p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- ===== VARIANTS ===== --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-400/15 flex items-center justify-center">
                            <span class="text-sm">🎨</span>
                        </div>
                        <div>
                            <h2 class="font-[Fraunces] text-lg">Variantes</h2>
                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">Tailles, couleurs, stock</p>
                        </div>
                    </div>
                    <button type="button" onclick="addVariant()"
                            class="flex items-center gap-2 px-4 py-2 bg-[#C9A24B]/10 border border-[#C9A24B]/30 text-[#C9A24B] font-[IBM_Plex_Mono] text-[10px] uppercase tracking-wider rounded-lg hover:bg-[#C9A24B]/20 transition-colors">
                        + Ajouter
                    </button>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Presets --}}
                    <div class="space-y-3">
                        <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788]">
                            Remplissage automatique
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @php
                                $presets = [
                                    ['key'=>'clothes', 'icon'=>'👕', 'label'=>'Vêtements',  'sub'=>'XS → XXL'],
                                    ['key'=>'shoes',   'icon'=>'👟', 'label'=>'Chaussures', 'sub'=>'38 → 46'],
                                    ['key'=>'watch',   'icon'=>'⌚', 'label'=>'Accessoires','sub'=>'Taille unique'],
                                    ['key'=>'kids',    'icon'=>'🧒', 'label'=>'Enfants',    'sub'=>'2 → 14 ans'],
                                    ['key'=>'baby',    'icon'=>'👶', 'label'=>'Bébé',       'sub'=>'0M → 24M'],
                                    ['key'=>'belt',    'icon'=>'👜', 'label'=>'Ceintures',  'sub'=>'85 → 115 cm'],
                                ];
                            @endphp
                            @foreach ($presets as $preset)
                                <button type="button" onclick="fillPreset('{{ $preset['key'] }}')"
                                        class="flex items-center gap-2 px-3 py-2.5 bg-[#14151C] border border-white/10 rounded-xl hover:border-[#C9A24B]/40 hover:bg-[#C9A24B]/5 transition-all group text-left">
                                    <span class="text-lg">{{ $preset['icon'] }}</span>
                                    <div>
                                        <p class="font-[IBM_Plex_Mono] text-[10px] text-[#F6F3EC] group-hover:text-[#C9A24B] transition-colors">{{ $preset['label'] }}</p>
                                        <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">{{ $preset['sub'] }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Couleurs rapides --}}
                    <div class="space-y-2">
                        <p class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788]">
                            Appliquer une couleur à toutes les variantes
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $quickColors = [
                                    ['name'=>'Noir',     'hex'=>'#1a1a1a'],
                                    ['name'=>'Blanc',    'hex'=>'#f0f0f0'],
                                    ['name'=>'Gris',     'hex'=>'#808080'],
                                    ['name'=>'Marine',   'hex'=>'#1e3a5f'],
                                    ['name'=>'Bleu',     'hex'=>'#1d4ed8'],
                                    ['name'=>'Rouge',    'hex'=>'#dc2626'],
                                    ['name'=>'Vert',     'hex'=>'#16a34a'],
                                    ['name'=>'Kaki',     'hex'=>'#6b7028'],
                                    ['name'=>'Beige',    'hex'=>'#d4b896'],
                                    ['name'=>'Camel',    'hex'=>'#c2883a'],
                                    ['name'=>'Marron',   'hex'=>'#7c3f0a'],
                                    ['name'=>'Bordeaux', 'hex'=>'#881337'],
                                    ['name'=>'Rose',     'hex'=>'#ec4899'],
                                    ['name'=>'Violet',   'hex'=>'#7c3aed'],
                                    ['name'=>'Jaune',    'hex'=>'#eab308'],
                                    ['name'=>'Orange',   'hex'=>'#f97316'],
                                ];
                            @endphp
                            @foreach ($quickColors as $c)
                                <button type="button"
                                        onclick="applyColorToAll('{{ $c['name'] }}', '{{ $c['hex'] }}')"
                                        title="{{ $c['name'] }}"
                                        class="group relative w-8 h-8 rounded-full border-2 border-white/20 hover:border-white/60 hover:scale-110 transition-all"
                                        style="background: {{ $c['hex'] }}">
                                    <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 font-[IBM_Plex_Mono] text-[8px] text-[#9C9788] whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ $c['name'] }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tableau variants --}}
                    <div class="space-y-2" id="variants-container">

                        {{-- Header --}}
                        <div class="grid grid-cols-12 gap-2 px-3 pb-1">
                            <p class="col-span-3 font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788]">Taille</p>
                            <p class="col-span-4 font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788]">Couleur</p>
                            <p class="col-span-2 font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788]">Stock</p>
                            <p class="col-span-2 font-[IBM_Plex_Mono] text-[9px] uppercase tracking-widest text-[#9C9788]">+Prix</p>
                            <p class="col-span-1"></p>
                        </div>

                        {{-- Initial variant --}}
                        <div class="variant-row grid grid-cols-12 gap-2 items-center bg-[#14151C] border border-white/8 rounded-xl px-3 py-3">
                            <div class="col-span-3">
                                <input type="text" name="variants[0][size]"
                                       placeholder="S, 42..."
                                       class="variant-size w-full bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg">
                            </div>
                            <div class="col-span-4 flex gap-1.5">
                                <input type="text" name="variants[0][color]"
                                       placeholder="Noir..."
                                       class="variant-color flex-1 min-w-0 bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg">
                                <input type="color" name="variants[0][color_hex]" value="#1a1a1a"
                                       class="variant-color-hex w-9 h-9 rounded-lg border border-white/15 bg-transparent cursor-pointer p-0.5 flex-shrink-0">
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="variants[0][stock]" value="10" min="0"
                                       class="variant-stock w-full bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-center">
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="variants[0][extra_price]" value="0" min="0"
                                       class="variant-extra w-full bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-center">
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <button type="button" onclick="removeVariant(this)"
                                        class="text-[#9C9788] hover:text-red-400 transition-colors text-sm">✕</button>
                            </div>
                        </div>
                    </div>

                    <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]/50">
                        💡 Cliquez sur un preset pour générer automatiquement toutes les variantes
                    </p>
                </div>
            </div>

        </div>

        {{-- ===== COLONNE DROITE (1/3) ===== --}}
        <div class="space-y-6">

            {{-- Catégorie --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/15 flex items-center justify-center">
                        <span class="text-sm">📁</span>
                    </div>
                    <h2 class="font-[Fraunces] text-lg">Catégorie</h2>
                </div>
                <div class="p-6">
                    @php
                        $mainCategories = \App\Models\Category::whereNull('parent_id')
                            ->with('children')
                            ->orderBy('order')
                            ->get();
                    @endphp

                    @if ($mainCategories->isEmpty())
                        <div class="bg-yellow-400/10 border border-yellow-400/20 rounded-xl p-4">
                            <p class="font-[IBM_Plex_Mono] text-[10px] text-yellow-400">
                                ⚠️ Aucune catégorie.
                                <a href="{{ route('admin.categories.create') }}" class="underline">Créer →</a>
                            </p>
                        </div>
                    @else
                        <select name="category_id" required
                                class="w-full bg-[#14151C] border border-white/15 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-xl text-[#F6F3EC]">
                            <option value="">— Choisir —</option>
                            @foreach ($mainCategories as $main)
                                <optgroup label="── {{ $main->name }} ──" style="color:#C9A24B;background:#14151C;">
                                    @foreach ($main->children as $child)
                                        <option value="{{ $child->id }}"
                                                {{ old('category_id') == $child->id ? 'selected' : '' }}
                                                style="color:#F6F3EC;">
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-400 text-[10px] mt-2">{{ $message }}</p> @enderror
                    @endif
                </div>
            </div>

            {{-- Statut --}}
            <div class="bg-[#1C1E27] border border-white/10 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#C9A24B]/15 flex items-center justify-center">
                        <span class="text-sm">⚡</span>
                    </div>
                    <h2 class="font-[Fraunces] text-lg">Statut</h2>
                </div>
                <div class="p-6 space-y-3">
                    <label class="flex items-center justify-between p-4 bg-[#14151C] rounded-xl border border-white/8 cursor-pointer hover:border-[#C9A24B]/30 transition-colors">
                        <div>
                            <p class="font-[Inter] text-sm font-medium">Produit actif</p>
                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] mt-0.5">Visible sur le site</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="sr-only peer" id="toggle-active">
                            <div class="w-11 h-6 bg-white/10 peer-checked:bg-[#C9A24B] rounded-full transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between p-4 bg-[#14151C] rounded-xl border border-white/8 cursor-pointer hover:border-[#C9A24B]/30 transition-colors">
                        <div>
                            <p class="font-[Inter] text-sm font-medium">Produit vedette</p>
                            <p class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788] mt-0.5">Affiché en page d'accueil</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_featured" value="1"
                                   class="sr-only peer" id="toggle-featured">
                            <div class="w-11 h-6 bg-white/10 peer-checked:bg-[#C9A24B] rounded-full transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="space-y-3">
                <button type="submit"
                        class="w-full py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-sm font-bold uppercase tracking-widest rounded-2xl hover:bg-[#dab564] transition-colors shadow-lg">
                    ✓ Créer le produit
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="block w-full py-3 text-center border border-white/10 text-[#9C9788] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-2xl hover:border-white/25 transition-colors">
                    Annuler
                </a>
            </div>

           {{-- Résumé variantes --}}
           <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-5">
                <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-4">
                    Résumé des variantes
                </p>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Variantes</span>
                        <span id="summary-variants"
                              class="font-[IBM_Plex_Mono] text-sm font-bold text-[#F6F3EC] bg-white/5 px-2.5 py-0.5 rounded-lg">
                            1
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Stock total</span>
                        <span id="summary-stock"
                              class="font-[IBM_Plex_Mono] text-sm font-bold text-[#C9A24B] bg-[#C9A24B]/10 px-2.5 py-0.5 rounded-lg">
                            10
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Stock moyen</span>
                        <span id="summary-avg"
                              class="font-[IBM_Plex_Mono] text-sm text-[#9C9788]">
                            10
                        </span>
                    </div>
                </div>

                {{-- Barre de progression stock --}}
                <div class="mt-4 pt-4 border-t border-white/8">
                    <div class="flex justify-between mb-1.5">
                        <span class="font-[IBM_Plex_Mono] text-[9px] text-[#9C9788]">Niveau de stock</span>
                        <span id="stock-status-label" class="font-[IBM_Plex_Mono] text-[9px] text-green-400">Bon</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-1.5">
                        <div id="stock-bar" class="h-1.5 rounded-full bg-green-400 transition-all duration-300" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let variantIndex = 1;

const presets = {
    clothes: ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
    shoes:   ['38', '39', '40', '41', '42', '43', '44', '45', '46'],
    watch:   ['Unique'],
    kids:    ['2 ans', '4 ans', '6 ans', '8 ans', '10 ans', '12 ans', '14 ans'],
    baby:    ['0-3M', '3-6M', '6-9M', '9-12M', '12-18M', '18-24M'],
    belt:    ['85 cm', '90 cm', '95 cm', '100 cm', '105 cm', '110 cm', '115 cm'],
};

function fillPreset(type) {
    const container = document.getElementById('variants-container');
    // Garde uniquement le header (1er enfant)
    const header = container.firstElementChild;
    container.innerHTML = '';
    container.appendChild(header);
    variantIndex = 0;
    presets[type].forEach(size => addVariantRow(size, '', '#1a1a1a', 10, 0));
    updateSummary();
}

function addVariantRow(size='', color='', colorHex='#1a1a1a', stock=10, extra=0) {
    const idx = variantIndex;
    const div = document.createElement('div');
    div.className = 'variant-row grid grid-cols-12 gap-2 items-center bg-[#14151C] border border-white/8 rounded-xl px-3 py-3';
    div.innerHTML = `
        <div class="col-span-3">
            <input type="text" name="variants[${idx}][size]" value="${size}"
                   placeholder="S, 42..." class="variant-size w-full bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg">
        </div>
        <div class="col-span-4 flex gap-1.5">
            <input type="text" name="variants[${idx}][color]" value="${color}"
                   placeholder="Noir..." class="variant-color flex-1 min-w-0 bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg">
            <input type="color" name="variants[${idx}][color_hex]" value="${colorHex}"
                   class="variant-color-hex w-9 h-9 rounded-lg border border-white/15 bg-transparent cursor-pointer p-0.5 flex-shrink-0">
        </div>
        <div class="col-span-2">
            <input type="number" name="variants[${idx}][stock]" value="${stock}" min="0"
                   oninput="updateSummary()"
                   class="variant-stock w-full bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-center">
        </div>
        <div class="col-span-2">
            <input type="number" name="variants[${idx}][extra_price]" value="${extra}" min="0"
                   class="variant-extra w-full bg-transparent border border-white/15 px-2 py-2 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs rounded-lg text-center">
        </div>
        <div class="col-span-1 flex justify-center">
            <button type="button" onclick="removeVariant(this)"
                    class="text-[#9C9788] hover:text-red-400 transition-colors text-sm w-6 h-6 flex items-center justify-center rounded hover:bg-red-400/10">✕</button>
        </div>
    `;
    document.getElementById('variants-container').appendChild(div);
    variantIndex++;
    updateSummary();
}

function addVariant() { addVariantRow(); }

function removeVariant(btn) {
    const rows = document.querySelectorAll('.variant-row');
    if (rows.length > 1) {
        btn.closest('.variant-row').remove();
        updateSummary();
    }
}

function applyColorToAll(name, hex) {
    document.querySelectorAll('.variant-color').forEach(i => { if (!i.value) i.value = name; else i.value = name; });
    document.querySelectorAll('.variant-color-hex').forEach(i => i.value = hex);
}

function updateSummary() {
    const rows = document.querySelectorAll('.variant-row');
    let totalStock = 0;
    rows.forEach(r => {
        const s = r.querySelector('.variant-stock');
        if (s) totalStock += parseInt(s.value) || 0;
    });
    const count = rows.length;
    const avg   = count > 0 ? Math.round(totalStock / count) : 0;

    document.getElementById('summary-variants').textContent = count;
    document.getElementById('summary-stock').textContent    = totalStock;
    document.getElementById('summary-avg').textContent      = avg + ' / variante';

    // Barre + status
    const bar   = document.getElementById('stock-bar');
    const label = document.getElementById('stock-status-label');
    const pct   = Math.min(totalStock / (count * 20) * 100, 100);

    bar.style.width = pct + '%';

    if (totalStock === 0) {
        bar.className   = 'h-1.5 rounded-full bg-red-400 transition-all duration-300';
        label.className = 'font-[IBM_Plex_Mono] text-[9px] text-red-400';
        label.textContent = 'Rupture';
    } else if (avg <= 5) {
        bar.className   = 'h-1.5 rounded-full bg-yellow-400 transition-all duration-300';
        label.className = 'font-[IBM_Plex_Mono] text-[9px] text-yellow-400';
        label.textContent = 'Stock bas';
    } else {
        bar.className   = 'h-1.5 rounded-full bg-green-400 transition-all duration-300';
        label.className = 'font-[IBM_Plex_Mono] text-[9px] text-green-400';
        label.textContent = 'Bon';
    }
}
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-placeholder').classList.add('hidden');
            document.getElementById('preview-done').classList.remove('hidden');
            document.getElementById('preview-img').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

updateSummary();
</script>

@endsection