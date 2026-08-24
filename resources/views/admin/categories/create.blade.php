@extends('admin.layouts.app')
@section('title', 'Ajouter une catégorie')

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.categories.index') }}"
       class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
        ← Retour
    </a>
    <h1 class="font-[Fraunces] text-3xl">Ajouter une catégorie</h1>
</div>
    <div class="max-w-lg bg-[#14151C] border border-white/10 rounded p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Nom *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
                @error('name') <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Catégorie parente (optionnel)</label>
                <select name="parent_id"
                        class="w-full bg-[#0E0F14] border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none text-[#F6F3EC]">
                    <option value="">— Catégorie principale —</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">Ordre d'affichage</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                       class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none">
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-[#C9A24B]">
                <span class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Catégorie active</span>
            </label>

            <button type="submit"
                    class="w-full px-6 py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564]">
                Créer la catégorie
            </button>
        </form>
    </div>
@endsection