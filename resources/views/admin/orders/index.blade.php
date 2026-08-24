@extends('admin.layouts.app')

@section('title', 'Commandes')

@section('content')

    {{-- Empêcher tout débordement horizontal de cette page --}}
    <style>
        html,
        body {
            overflow-x: hidden !important;
            max-width: 100%;
        }

        #orders-page {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: hidden;
        }

        #orders-table-wrapper {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }

        #orders-table {
            width: 100%;
            max-width: 100%;
            table-layout: auto;
        }
    </style>

    <div id="orders-page">

        <h1 class="font-[Fraunces] text-3xl mb-6">Commandes</h1>

        {{-- Filtres --}}
        <div class="flex gap-2 mb-5 flex-wrap">
            @php
                $filters = [
                    'all'        => ['label'=>'Toutes',      'color'=>'text-[#9C9788]'],
                    'pending'    => ['label'=>'En attente',  'color'=>'text-yellow-400'],
                    'processing' => ['label'=>'En cours',    'color'=>'text-blue-400'],
                    'paid'       => ['label'=>'Payées',      'color'=>'text-green-400'],
                    'shipped'    => ['label'=>'Expédiées',   'color'=>'text-indigo-400'],
                    'delivered'  => ['label'=>'Livrées',     'color'=>'text-emerald-400'],
                    'cancelled'  => ['label'=>'Annulées',    'color'=>'text-red-400'],
                ];
            @endphp

            @foreach ($filters as $key => $f)
                @php $isActive = ($status ?? 'all') === $key; @endphp

                <a href="{{ route('admin.orders.index', $key !== 'all' ? ['status'=>$key] : []) }}"
                   class="font-[IBM_Plex_Mono] text-[12px] uppercase tracking-wider px-3 py-1.5 rounded-lg border transition-all
                          {{ $isActive ? $f['color'].' border-current/30 bg-white/5' : 'text-[#9C9788] border-white/10 hover:border-white/25' }}">
                    {{ $f['label'] }}
                    <span class="opacity-60">({{ $counts[$key] ?? $counts['all'] }})</span>
                </a>
            @endforeach
        </div>


        {{-- BULK SHIP --}}
        <form id="bulk-form"
              action="{{ route('admin.orders.bulk-ship') }}"
              method="POST">

            @csrf

            {{-- Bulk Bar --}}
            <div id="bulk-bar"
                 class="hidden mb-4 bg-[#1C1E27] border border-[#C9A24B]/30 rounded-xl px-4 py-3 flex flex-wrap items-center gap-3">

                <span id="bulk-count"
                      class="font-[IBM_Plex_Mono] text-xs text-[#C9A24B] font-bold">
                    0 sélectionné(s)
                </span>

                <select name="company"
                        required
                        class="bg-[#14151C] border border-white/20 px-3 py-2 font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] focus:border-[#C9A24B] outline-none">

                    <option value="">— Société —</option>
                    <option value="yalidine">🟡 Yalidine</option>
                    <option value="zr">🔵 ZR Express</option>
                    <option value="maystro">🟢 Maystro</option>
                    <option value="ecotrack">🟠 Ecotrack</option>

                </select>

                <div class="flex items-center gap-2">
                    <label class="font-[IBM_Plex_Mono] text-[14px] text-[#9C9788]">
                        Poids (kg)
                    </label>

                    <input type="number"
                           name="weight"
                           value="0.5"
                           min="0.1"
                           step="0.1"
                           class="w-16 bg-[#14151C] border border-white/20 px-2 py-2 font-[IBM_Plex_Mono] text-xs rounded-lg text-[#F6F3EC] focus:border-[#C9A24B] outline-none">
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest rounded-lg hover:bg-[#dab564] transition-colors font-bold">
                    🚚 Créer les envois
                </button>

                <button type="button"
                        onclick="deselectAll()"
                        class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] hover:text-red-400 transition-colors">
                    ✕ Annuler
                </button>

            </div>


            {{-- Table --}}
            <div class="w-full max-w-full rounded-2xl border border-white/10 bg-[#1C1E27] overflow-hidden">

                <div id="orders-table-wrapper">

                    <table id="orders-table">

                        <thead>
                            <tr class="border-b border-white/8 bg-white/2">

                                <th class="px-4 py-3">
                                    <input type="checkbox"
                                           id="select-all"
                                           onchange="toggleAll(this)"
                                           class="accent-[#C9A24B] w-4 h-4">
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788]">
                                    N°
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-widest text-[#9C9788]">
                                    Client
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                                    Wilaya
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                                    Total
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                                    Livraison
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                                    Statut
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                                    Tracking
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                                    Date
                                </th>

                                <th class="px-4 py-3 text-left font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788]">
                                    Action
                                </th>

                            </tr>
                        </thead>


                        <tbody>

                            @forelse ($orders as $order)

                                @php
                                    $sc = match($order->status) {
                                        'pending' => [
                                            'label'=>'En attente',
                                            'color'=>'text-yellow-400',
                                            'bg'=>'bg-yellow-400/10',
                                            'bl'=>'border-l-yellow-400'
                                        ],

                                        'processing' => [
                                            'label'=>'En cours',
                                            'color'=>'text-blue-400',
                                            'bg'=>'bg-blue-400/10',
                                            'bl'=>'border-l-blue-400'
                                        ],

                                        'paid' => [
                                            'label'=>'Payée ✓',
                                            'color'=>'text-green-400',
                                            'bg'=>'bg-green-400/10',
                                            'bl'=>'border-l-green-400'
                                        ],

                                        'shipped' => [
                                            'label'=>'Expédiée 🚚',
                                            'color'=>'text-indigo-400',
                                            'bg'=>'bg-indigo-400/10',
                                            'bl'=>'border-l-indigo-400'
                                        ],

                                        'delivered' => [
                                            'label'=>'Livrée ✅',
                                            'color'=>'text-emerald-400',
                                            'bg'=>'bg-emerald-400/10',
                                            'bl'=>'border-l-emerald-400'
                                        ],

                                        'cancelled' => [
                                            'label'=>'Annulée',
                                            'color'=>'text-red-400',
                                            'bg'=>'bg-red-400/10',
                                            'bl'=>'border-l-red-400'
                                        ],

                                        default => [
                                            'label'=>$order->status,
                                            'color'=>'text-[#9C9788]',
                                            'bg'=>'bg-white/5',
                                            'bl'=>'border-l-transparent'
                                        ],
                                    };
                                @endphp


                                <tr class="border-b border-white/4 hover:bg-white/2 transition-colors border-l-2 {{ $sc['bl'] }}">

                                    <td class="px-4 py-3">
                                        <input type="checkbox"
                                               name="order_ids[]"
                                               value="{{ $order->id }}"
                                               onchange="updateBulkBar()"
                                               class="accent-[#C9A24B] w-4 h-4 order-checkbox">
                                    </td>


                                    <td class="px-4 py-3">
                                        <span class="font-[IBM_Plex_Mono] text-[12px] text-[#C9A24B]">
                                            {{ $order->order_number }}
                                        </span>
                                    </td>


                                    <td class="px-4 py-3">

                                        <p class="font-[Inter] text-sm leading-tight">
                                            {{ $order->shipping_name }}
                                        </p>

                                        <p class="font-[IBM_Plex_Mono] text-[12px] text-[#9C9788] truncate max-w-[140px]">
                                            {{ $order->user?->email ?? $order->guest_email ?? '—' }}
                                        </p>

                                    </td>


                                    <td class="px-4 py-3 font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">
                                        {{ $order->shipping_wilaya }}
                                    </td>


                                    <td class="px-4 py-3 font-[IBM_Plex_Mono] text-sm font-bold text-[#C9A24B] whitespace-nowrap">
                                        {{ number_format($order->total, 0, ',', ' ') }} DA
                                    </td>


                                    <td class="px-4 py-3">

                                        <span class="font-[IBM_Plex_Mono] text-[11px] px-2 py-0.5 rounded-full whitespace-nowrap
                                            {{ $order->delivery_type === 'stop_desk'
                                                ? 'text-blue-300 bg-blue-400/10'
                                                : 'text-purple-300 bg-purple-400/10' }}">

                                            {{ $order->delivery_type === 'stop_desk'
                                                ? '🏢 Stop Desk'
                                                : '🏠 Domicile' }}

                                        </span>

                                    </td>


                                    <td class="px-4 py-3">

                                        <span class="font-[IBM_Plex_Mono] text-[11px] px-2 py-0.5 rounded-full border
                                            {{ $sc['color'] }}
                                            {{ $sc['bg'] }}
                                            whitespace-nowrap">

                                            {{ $sc['label'] }}

                                        </span>

                                    </td>


                                    <td class="px-4 py-3">

                                        @if ($order->tracking_number)

                                            <p class="font-[IBM_Plex_Mono] text-[10px] text-green-400">
                                                {{ $order->tracking_number }}
                                            </p>

                                            <p class="font-[IBM_Plex_Mono] text-[15px] text-[#9C9788] uppercase">
                                                {{ $order->delivery_company }}
                                            </p>

                                        @else

                                            <span class="text-[#9C9788] text-xs">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-4 py-3 font-[IBM_Plex_Mono] text-[13px] text-[#9C9788] whitespace-nowrap">

                                        {{ $order->created_at->format('d/m/Y') }}

                                        <br>

                                        <span class="text-[9px]">
                                            {{ $order->created_at->format('H:i') }}
                                        </span>

                                    </td>


                                    <td class="px-4 py-3">

                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="font-[IBM_Plex_Mono] text-[12px] text-[#C9A24B] hover:underline uppercase whitespace-nowrap">

                                            Voir →

                                        </a>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="10"
                                        class="px-6 py-16 text-center">

                                        <p class="font-[Fraunces] text-xl text-[#9C9788]">
                                            Aucune commande
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($orders->hasPages())

                    <div class="px-6 py-4 border-t border-white/8">
                        {{ $orders->links() }}
                    </div>

                @endif

            </div>

        </form>

    </div>


    <script>

        function toggleAll(master) {

            document
                .querySelectorAll('.order-checkbox')
                .forEach(cb => cb.checked = master.checked);

            updateBulkBar();
        }


        function updateBulkBar() {

            const checked = document.querySelectorAll('.order-checkbox:checked');

            const bar = document.getElementById('bulk-bar');

            const count = document.getElementById('bulk-count');


            if (checked.length > 0) {

                bar.classList.remove('hidden');

                count.textContent =
                    checked.length + ' sélectionné(s)';

            } else {

                bar.classList.add('hidden');

                document.getElementById('select-all').checked = false;
            }
        }


        function deselectAll() {

            document
                .querySelectorAll('.order-checkbox, #select-all')
                .forEach(cb => cb.checked = false);

            document
                .getElementById('bulk-bar')
                .classList.add('hidden');
        }

    </script>

@endsection