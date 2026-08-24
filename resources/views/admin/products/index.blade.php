@extends('admin.layouts.app')
@section('title', 'Produits')

@section('content')

<div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-[Fraunces] text-3xl">Produits</h1>
            <p class="font-[IBM_Plex_Mono] text-[15px] text-[#9C9788] mt-1">{{ $products->total() }} produits</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Filter Stock --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.products.index') }}"
                   class="font-[IBM_Plex_Mono] text-[12px] px-3 py-2 rounded-lg border transition-all
                          {{ ! request('stock') ? 'bg-white/5 border-white/20 text-[#F6F3EC]' : 'border-white/10 text-[#9C9788] hover:border-white/25' }}">
                    Tous
                </a>
                <a href="{{ route('admin.products.index', ['stock' => 'low']) }}"
                   class="font-[IBM_Plex_Mono] text-[12px] px-3 py-2 rounded-lg border transition-all flex items-center gap-1.5
                          {{ request('stock') === 'low' ? 'bg-yellow-400/15 border-yellow-400/30 text-yellow-400' : 'border-white/10 text-[#9C9788] hover:border-yellow-400/30 hover:text-yellow-400' }}">
                    ⚠️ Stock faible
                    @php $lowCount = \App\Models\Product::whereHas('variants', fn($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5))->count(); @endphp
                    @if ($lowCount > 0)
                        <span class="bg-yellow-400 text-[#14151C] text-[8px] font-bold rounded-full px-1.5 py-0.5">{{ $lowCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.products.index', ['stock' => 'out']) }}"
                   class="font-[IBM_Plex_Mono] text-[12px] px-3 py-2 rounded-lg border transition-all flex items-center gap-1.5
                          {{ request('stock') === 'out' ? 'bg-red-400/15 border-red-400/30 text-red-400' : 'border-white/10 text-[#9C9788] hover:border-red-400/30 hover:text-red-400' }}">
                    🚫 Rupture
                    @php $outCount = \App\Models\Product::whereDoesntHave('variants', fn($q) => $q->where('stock', '>', 0))->count(); @endphp
                    @if ($outCount > 0)
                        <span class="bg-red-400 text-white text-[9px] font-bold rounded-full px-1.5 py-0.5">{{ $outCount }}</span>
                    @endif
                </a>
            </div>
            <a href="{{ route('admin.products.create') }}"
               class="flex items-center gap-2 px-5 py-2.5 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-xl hover:bg-[#dab564] transition-colors">
                + Ajouter
            </a>
        </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-[#1C1E27] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/8 bg-white/2">
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">Image</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">Produit</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">Catégorie</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">Prix</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">Stock</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">Statut</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $totalStock = $product->variants->sum('stock');
                            $stockStatus = $totalStock === 0 ? 'out' : ($totalStock <= 5 ? 'low' : 'ok');
                        @endphp
                  <tr class="border-b border-white/4 hover:bg-white/2 transition-colors"
                            id="product-row-{{ $product->id }}">

                            {{-- Image --}}
                            <td class="px-5 py-4">
                                <div class="w-12 h-14 rounded-xl bg-white/5 overflow-hidden flex-shrink-0">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-lg">👕</div>
                                    @endif
                                </div>
                            </td>

                            {{-- Produit --}}
                            <td class="px-5 py-4">
                                <p class="font-[Inter] text-sm font-medium">{{ $product->name }}</p>
                                @if ($product->is_featured)
                                    <span class="font-[IBM_Plex_Mono] text-[13px] text-[#C9A24B] bg-[#C9A24B]/10 px-2 py-0.5 rounded-full">⭐ Vedette</span>
                                @endif
                                @if ($product->sale_price)
                                    <span class="font-[IBM_Plex_Mono] text-[12px] text-orange-400 bg-orange-400/10 px-2 py-0.5 rounded-full ml-1">Promo</span>
                                @endif
                            </td>

                            {{-- Catégorie --}}
                            <td class="px-5 py-4 font-[IBM_Plex_Mono] text-[12px] text-[#9C9788]">
                                {{ $product->category->name }}
                            </td>

                            {{-- Prix --}}
                            <td class="px-5 py-4">
                                <p class="font-[IBM_Plex_Mono] text-sm text-[#C9A24B]">{{ number_format($product->price, 0, ',', ' ') }} DA</p>
                                @if ($product->sale_price)
                                    <p class="font-[IBM_Plex_Mono] text-[10px] text-orange-400 line-through">{{ number_format($product->sale_price, 0, ',', ' ') }} DA</p>
                                @endif
                            </td>

                            {{-- Stock (éditable) --}}
                            <td class="px-5 py-4">
                                @if ($product->variants->count() === 1)
                                    {{-- Stock direct modifiable --}}
                                    @php $variant = $product->variants->first(); @endphp
                                    <div class="flex items-center gap-2">
                                        <input type="number" min="0"
                                               value="{{ $variant->stock }}"
                                               data-variant="{{ $variant->id }}"
                                               data-product="{{ $product->id }}"
                                               onchange="updateStock(this)"
                                               class="w-16 bg-[#14151C] border border-white/15 px-2 py-1.5 text-center font-[IBM_Plex_Mono] text-xs rounded-lg focus:border-[#C9A24B] outline-none
                                                      {{ $stockStatus === 'out' ? 'border-red-400/40 text-red-400' : ($stockStatus === 'low' ? 'border-yellow-400/40 text-yellow-400' : 'text-green-400') }}">
                                        @if ($stockStatus === 'out')
                                            <span class="text-red-400 text-xs">🚫</span>
                                        @elseif ($stockStatus === 'low')
                                            <span class="text-yellow-400 text-xs">⚠️</span>
                                        @endif
                                    </div>
                                @else
                                    {{-- Multi-variants --}}
                                    <div class="space-y-1">
                                        <span class="font-[IBM_Plex_Mono] text-xs
                                            {{ $stockStatus === 'out' ? 'text-red-400' : ($stockStatus === 'low' ? 'text-yellow-400' : 'text-green-400') }}">
                                            {{ $totalStock }} total
                                        </span>
                                        <p class="font-[IBM_Plex_Mono] text-[14px] text-[#9C9788]">{{ $product->variants->count() }} variantes</p>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="font-[IBM_Plex_Mono] text-[15px] text-[#C9A24B] hover:underline">
                                            Modifier stocks →
                                        </a>
                                    </div>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td class="px-5 py-4" id="status-{{ $product->id }}">
                                @if ($product->is_active && $totalStock > 0)
                                    <span class="font-[IBM_Plex_Mono] text-[10px] px-2.5 py-1 rounded-full text-green-400 bg-green-400/10 border border-green-400/20">
                                        ● Actif
                                    </span>
                                @elseif ($totalStock === 0)
                                    <span class="font-[IBM_Plex_Mono] text-[10px] px-2.5 py-1 rounded-full text-red-400 bg-red-400/10 border border-red-400/20">
                                        ● Rupture
                                    </span>
                                @else
                                    <span class="font-[IBM_Plex_Mono] text-[10px] px-2.5 py-1 rounded-full text-[#9C9788] bg-white/5">
                                        ● Inactif
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="font-[IBM_Plex_Mono] text-[10px] text-[#C9A24B] hover:underline uppercase tracking-wider">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                          onsubmit="return confirm('Supprimer « {{ $product->name }} » ?')">
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
                            <td colspan="7" class="px-6 py-16 text-center">
                                <p class="font-[Fraunces] text-xl text-[#9C9788] mb-2">Aucun produit</p>
                                <a href="{{ route('admin.products.create') }}" class="font-[IBM_Plex_Mono] text-xs text-[#C9A24B] hover:underline">
                                    Ajouter le premier produit →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="px-6 py-4 border-t border-white/8">{{ $products->links() }}</div>
        @endif
    </div>

    <script>
        function updateStock(input) {
            const variantId = input.dataset.variant;
            const productId = input.dataset.product;
            const newStock  = parseInt(input.value) || 0;

            fetch(`/admin/variants/${variantId}/stock`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ stock: newStock }),
            })
            .then(res => res.json())
            .then(data => {
                const statusCell = document.getElementById('status-' + productId);

                if (data.status === 'deactivated' || data.total_stock === 0) {
                    input.className = input.className.replace(/text-\w+/g, '') + ' text-red-400';
                    input.style.borderColor = 'rgba(248,113,113,0.4)';
                    statusCell.innerHTML = `<span class="font-[IBM_Plex_Mono] text-[10px] px-2.5 py-1 rounded-full text-red-400 bg-red-400/10 border border-red-400/20">● Rupture</span>`;
                } else if (data.total_stock <= 5) {
                    input.style.borderColor = 'rgba(251,191,36,0.4)';
                    statusCell.innerHTML = `<span class="font-[IBM_Plex_Mono] text-[10px] px-2.5 py-1 rounded-full text-yellow-400 bg-yellow-400/10 border border-yellow-400/20">⚠️ Stock bas</span>`;
                } else {
                    input.style.borderColor = 'rgba(74,222,128,0.4)';
                    statusCell.innerHTML = `<span class="font-[IBM_Plex_Mono] text-[10px] px-2.5 py-1 rounded-full text-green-400 bg-green-400/10 border border-green-400/20">● Actif</span>`;
                }
            })
            .catch(() => alert('Erreur lors de la mise à jour du stock'));
        }
    </script>

    {{-- CSRF meta (requis pour AJAX) --}}
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

@endsection