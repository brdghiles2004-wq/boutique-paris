@extends('admin.layouts.app')
@section('title', 'Catégories')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-[Fraunces] text-3xl">Catégories</h1>
            <p class="font-[IBM_Plex_Mono] text-[15px] text-[#9C9788] mt-1">{{ $categories->count() }} catégories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-[#C9A24B] text-[#0A0B0F] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors">
            + Ajouter
        </a>
    </div>

    <div class="rounded-xl border border-white/8 bg-[#0E1018] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/8 bg-white/2">
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Nom</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Catégorie parente</th>
                        <th class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Sous-cat.</th>
                        <th class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Produits</th>
                        <th class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Actif</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr class="border-b border-white/4 hover:bg-white/2 transition-colors {{ ! $cat->parent_id ? 'bg-[#C9A24B]/3' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    @if ($cat->parent_id)
                                        <span class="w-4 h-px bg-white/20 inline-block flex-shrink-0"></span>
                                    @endif
                                    <span class="font-[Inter] text-sm {{ ! $cat->parent_id ? 'font-semibold text-[#C9A24B]' : '' }}">
                                        {{ $cat->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-[IBM_Plex_Mono] text-[14px] text-[#9C9788]">
                                {{ $cat->parent?->name ?? '— Principale —' }}
                            </td>
                            <td class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-sm">
                                {{ $cat->children->count() }}
                            </td>
                            <td class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-sm">
                                {{ $cat->products->count() }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-[IBM_Plex_Mono] text-[12px] px-2 py-0.5 rounded-full
                                    {{ $cat->is_active
                                        ? 'text-green-400 bg-green-400/10'
                                        : 'text-red-400 bg-red-400/10' }}">
                                    {{ $cat->is_active ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="font-[IBM_Plex_Mono] text-[10px] text-[#C9A24B] hover:underline uppercase tracking-wider">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                          onsubmit="return confirm('Supprimer « {{ $cat->name }} » ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="font-[IBM_Plex_Mono] text-[10px] text-red-400 hover:underline uppercase tracking-wider">
                                            Suppr.
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Aucune catégorie</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection