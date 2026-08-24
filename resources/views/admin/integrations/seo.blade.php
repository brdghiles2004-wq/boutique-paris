@extends('admin.layouts.app')
@section('title', 'SEO')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.integrations.index') }}"
           class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
            ← Retour
        </a>
        <h1 class="font-[Fraunces] text-3xl">🔍 SEO</h1>
    </div>

    <div class="max-w-2xl space-y-6">

        {{-- Meta Tags --}}
        <form action="{{ route('admin.integrations.seo.save') }}" method="POST">
            @csrf
            <div class="bg-[#1C1E27] border border-white/10 rounded-xl p-6 space-y-5">
                <h2 class="font-[Fraunces] text-lg mb-4">Meta Tags</h2>

                @php
                    $seoFields = [
                        ['name' => 'meta_title',       'label' => 'Titre SEO',       'placeholder' => 'Boutique Paris — Mode pour toute la famille'],
                        ['name' => 'meta_description', 'label' => 'Description SEO', 'textarea' => true, 'placeholder' => 'Description de votre boutique...'],
                        ['name' => 'meta_keywords',    'label' => 'Mots-clés',       'placeholder' => 'vêtements, mode, algérie, boutique en ligne'],
                        ['name' => 'og_image',         'label' => 'Image OG (URL)',   'placeholder' => 'https://...'],
                        ['name' => 'gsc_verification', 'label' => 'Google Search Console (code de vérification)', 'placeholder' => 'Entrez le code meta verification'],
                    ];
                @endphp

                @foreach ($seoFields as $field)
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            {{ $field['label'] }}
                        </label>
                        @if (!empty($field['textarea']))
                            <textarea name="{{ $field['name'] }}" rows="3" placeholder="{{ $field['placeholder'] }}"
                                      class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs">{{ \App\Models\Setting::get($field['name']) }}</textarea>
                        @else
                            <input type="text" name="{{ $field['name'] }}"
                                   value="{{ \App\Models\Setting::get($field['name']) }}"
                                   placeholder="{{ $field['placeholder'] }}"
                                   class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none font-[IBM_Plex_Mono] text-xs">
                        @endif
                    </div>
                @endforeach

                <button type="submit"
                        class="px-6 py-3 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors">
                    Enregistrer les meta tags
                </button>
            </div>
        </form>

        {{-- Sitemap --}}
        <div class="bg-[#1C1E27] border border-white/10 rounded-xl p-6">
            <h2 class="font-[Fraunces] text-lg mb-2">Sitemap XML</h2>
            <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] mb-5">
                Génère automatiquement le sitemap avec toutes les pages, catégories et produits actifs.
                Soumettez-le à
                <a href="https://search.google.com/search-console" target="_blank" class="text-[#C9A24B] hover:underline">Google Search Console</a>.
            </p>

            <div class="flex items-center gap-4">
                <form action="{{ route('admin.integrations.sitemap') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-5 py-2.5 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors">
                        Régénérer le Sitemap
                    </button>
                </form>
                <a href="/sitemap.xml" target="_blank"
                   class="px-5 py-2.5 border border-white/10 font-[IBM_Plex_Mono] text-xs text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 rounded-lg transition-colors">
                    Voir sitemap.xml ↗
                </a>
            </div>
        </div>
    </div>

@endsection