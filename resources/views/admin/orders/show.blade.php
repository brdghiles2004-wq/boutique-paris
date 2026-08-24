@extends('admin.layouts.app')
@section('title', 'Commande ' . $order->order_number)

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('admin.orders.index') }}"
       class="flex items-center gap-2 px-3 py-2 border border-white/10 rounded-lg font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 transition-all">
        ← Retour
    </a>
    <h1 class="font-[Fraunces] text-3xl">{{ $order->order_number }}</h1>
</div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Infos commande --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Articles --}}
            <div class="bg-[#14151C] border border-white/10 rounded p-6">
                <h2 class="font-[Fraunces] text-lg mb-4">Articles commandés</h2>
                <div class="space-y-3">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between font-[IBM_Plex_Mono] text-xs border-b border-white/5 pb-3">
                            <div>
                                <p class="text-[#F6F3EC]">{{ $item->product_name }}</p>
                                <p class="text-[#9C9788]">{{ $item->variant_label }} × {{ $item->quantity }}</p>
                            </div>
                            <p class="text-[#C9A24B]">{{ number_format($item->total, 0, ',', ' ') }} DA</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 space-y-2 font-[IBM_Plex_Mono] text-xs">
                    <div class="flex justify-between text-[#9C9788]">
                        <span>Sous-total</span><span>{{ number_format($order->subtotal, 0, ',', ' ') }} DA</span>
                    </div>
                    <div class="flex justify-between text-[#9C9788]">
                        <span>Livraison</span><span>{{ number_format($order->shipping_cost, 0, ',', ' ') }} DA</span>
                    </div>
                    <div class="flex justify-between text-[#C9A24B] text-sm font-bold border-t border-white/10 pt-2">
                        <span>Total</span><span>{{ number_format($order->total, 0, ',', ' ') }} DA</span>
                    </div>
                </div>
            </div>

            {{-- Livraison --}}
            <div class="bg-[#14151C] border border-white/10 rounded p-6">
                <h2 class="font-[Fraunces] text-lg mb-4">Adresse de livraison</h2>
                <div class="font-[IBM_Plex_Mono] text-xs text-[#9C9788] space-y-1">
                    <p class="text-[#F6F3EC] text-sm">{{ $order->shipping_name }}</p>
                    <p>📞 {{ $order->shipping_phone }}</p>
                    <p>📍 {{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_commune ?? '' }}, {{ $order->shipping_wilaya }}</p>
                    <p class="mt-2">
                        {{ $order->delivery_type === 'stop_desk' ? '🏢 Stop Desk' : '🏠 À Domicile' }}
                    </p>
                    @if ($order->guest_email)
                        <p class="mt-2 text-[#C9A24B]">📧 {{ $order->guest_email }} (invité)</p>
                    @endif
                    @if ($order->notes)
                        <p class="mt-2 border-t border-white/10 pt-2">💬 {{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statut --}}
        <div class="space-y-6">
            <div class="bg-[#14151C] border border-white/10 rounded p-6">
                <h2 class="font-[Fraunces] text-lg mb-4">Statut de la commande</h2>

                @php $colors = ['pending'=>'text-yellow-400','paid'=>'text-green-400','shipped'=>'text-blue-400','delivered'=>'text-green-500','cancelled'=>'text-red-400','refunded'=>'text-orange-400']; @endphp

                <p class="font-[IBM_Plex_Mono] text-xs uppercase {{ $colors[$order->status] ?? '' }} mb-6">
                    ● {{ $order->status }}
                </p>

                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status"
                            class="w-full bg-[#0E0F14] border border-white/20 px-3 py-2 focus:border-[#C9A24B] outline-none text-[#F6F3EC] font-[IBM_Plex_Mono] text-xs">
                        @foreach(['pending','processing','paid','shipped','delivered','cancelled','refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full py-2 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564]">
                        Mettre à jour
                    </button>
                </form>
            </div>

            {{-- Paiement --}}
            @if ($order->payments->isNotEmpty())
                <div class="bg-[#1C1E27] border border-white/10 rounded-xl p-6">
                    <h2 class="font-[Fraunces] text-lg mb-4">Paiement</h2>
                    @foreach ($order->payments as $payment)
                        <div class="font-[IBM_Plex_Mono] text-xs space-y-2">
                            <div class="flex justify-between">
                                <span class="text-[#9C9788]">Gateway</span>
                                <span class="text-[#F6F3EC] uppercase">{{ $payment->gateway }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#9C9788]">Statut</span>
                                <span class="{{ $payment->status === 'completed' ? 'text-green-400' : 'text-yellow-400' }}">
                                    {{ $payment->status }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#9C9788]">Montant</span>
                                <span class="text-[#C9A24B]">{{ $payment->amount }} {{ $payment->currency }}</span>
                            </div>
                            @if ($payment->proof_notes)
                                <div class="flex justify-between">
                                    <span class="text-[#9C9788]">Référence</span>
                                    <span class="text-[#F6F3EC]">{{ $payment->proof_notes }}</span>
                                </div>
                            @endif
                            @if ($payment->proof_image)
                                <div class="mt-4 pt-4 border-t border-white/10">
                                    <p class="text-[#9C9788] mb-2">📎 Reçu de paiement</p>
                                    <a href="{{ asset('storage/' . $payment->proof_image) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $payment->proof_image) }}"
                                             class="max-w-full rounded-lg border border-white/10 hover:opacity-80 transition-opacity"
                                             style="max-height: 300px; object-fit: contain;">
                                    </a>
                                    <a href="{{ asset('storage/' . $payment->proof_image) }}"
                                       target="_blank" download
                                       class="inline-block mt-2 text-[#C9A24B] hover:underline text-[10px]">
                                        ⬇ Télécharger le reçu
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection