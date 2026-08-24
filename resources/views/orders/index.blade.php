@extends('admin.layouts.app')
@section('title', 'Commandes')

@section('content')
    <h1 class="font-[Fraunces] text-3xl mb-6">Commandes</h1>

    {{-- Filtres --}}
    <div class="flex gap-2 mb-6 flex-wrap">
        @foreach(['all'=>'Toutes','pending'=>'En attente','paid'=>'Payées','shipped'=>'Expédiées','delivered'=>'Livrées','cancelled'=>'Annulées'] as $key => $label)
            <a href="{{ route('admin.orders.index', $key !== 'all' ? ['status' => $key] : []) }}"
               class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest px-3 py-1.5 border transition-colors
                      {{ ($status ?? 'all') === $key ? 'border-[#C9A24B] text-[#C9A24B]' : 'border-white/20 text-[#9C9788] hover:border-white/40' }}">
                {{ $label }} ({{ $counts[$key] ?? $counts['all'] }})
            </a>
        @endforeach
    </div>

    <div class="bg-[#14151C] border border-white/10 rounded overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    @foreach(['N°','Client','Wilaya','Total','Livraison','Statut','Date','Action'] as $h)
                        <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788]">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse ($orders as $order)
                    @php $colors = ['pending'=>'text-yellow-400','paid'=>'text-green-400','shipped'=>'text-blue-400','delivered'=>'text-green-500','cancelled'=>'text-red-400','refunded'=>'text-orange-400']; @endphp
                    <tr class="hover:bg-white/2">
                        <td class="px-4 py-3 font-[IBM_Plex_Mono] text-xs">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 font-[IBM_Plex_Mono] text-xs text-[#9C9788]">{{ $order->shipping_name }}</td>
                        <td class="px-4 py-3 font-[IBM_Plex_Mono] text-xs text-[#9C9788]">{{ $order->shipping_wilaya }}</td>
                        <td class="px-4 py-3 font-[IBM_Plex_Mono] text-xs text-[#C9A24B]">{{ number_format($order->total, 0, ',', ' ') }} DA</td>
                        <td class="px-4 py-3 font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">{{ $order->delivery_type === 'stop_desk' ? 'Stop Desk' : 'À Domicile' }}</td>
                        <td class="px-4 py-3 font-[IBM_Plex_Mono] text-[10px] uppercase {{ $colors[$order->status] ?? 'text-[#9C9788]' }}">{{ $order->status }}</td>
                        <td class="px-4 py-3 font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-[IBM_Plex_Mono] text-[10px] text-[#C9A24B] hover:underline">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Aucune commande</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>
@endsection