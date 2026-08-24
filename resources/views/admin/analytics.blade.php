@extends('admin.layouts.app')
@section('title', 'Analytics')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h1 class="font-[Fraunces] text-3xl">Analytics</h1>

        {{-- Date Range Filter --}}
        <div class="flex items-center gap-3 bg-[#1C1E27] border border-white/10 rounded-xl px-4 py-2">
            <div class="flex gap-2">
                @php
                    $ranges = [
                        'today'   => "Aujourd'hui",
                        'week'    => '7 jours',
                        'month'   => '30 jours',
                        'year'    => '1 an',
                        'custom'  => 'Personnalisé',
                    ];
                    $currentRange = request('range', 'month');
                @endphp
                @foreach ($ranges as $key => $label)
                    @if ($key !== 'custom')
                        <a href="{{ route('admin.analytics', ['range' => $key]) }}"
                           class="font-[IBM_Plex_Mono] text-[10px] px-3 py-1.5 rounded-lg transition-all
                                  {{ $currentRange === $key
                                      ? 'bg-[#C9A24B] text-[#14151C] font-bold'
                                      : 'text-[#9C9788] hover:text-[#F6F3EC] hover:bg-white/5' }}">
                            {{ $label }}
                        </a>
                    @endif
                @endforeach
            </div>

            <span class="text-white/20">|</span>

            {{-- Custom date --}}
            <form method="GET" action="{{ route('admin.analytics') }}" class="flex items-center gap-2">
                <input type="hidden" name="range" value="custom">
                <input type="date" name="date_from"
                       value="{{ request('date_from', now()->subMonth()->format('Y-m-d')) }}"
                       class="bg-transparent border border-white/20 rounded-lg px-2 py-1 font-[IBM_Plex_Mono] text-[10px] text-[#F6F3EC] focus:border-[#C9A24B] outline-none">
                <span class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">→</span>
                <input type="date" name="date_to"
                       value="{{ request('date_to', now()->format('Y-m-d')) }}"
                       class="bg-transparent border border-white/20 rounded-lg px-2 py-1 font-[IBM_Plex_Mono] text-[10px] text-[#F6F3EC] focus:border-[#C9A24B] outline-none">
                <button type="submit"
                        class="px-3 py-1.5 bg-[#C9A24B]/20 text-[#C9A24B] font-[IBM_Plex_Mono] text-[10px] rounded-lg hover:bg-[#C9A24B]/30 transition-colors">
                    OK
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-6">
            <h2 class="font-[Fraunces] text-lg mb-4">Ventes mensuelles</h2>
            <canvas id="monthlySalesChart" height="220"></canvas>
        </div>
        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-6">
            <h2 class="font-[Fraunces] text-lg mb-4">Statut des commandes</h2>
            <canvas id="orderStatusChart" height="220"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-6">
            <h2 class="font-[Fraunces] text-lg mb-4">Top 5 produits</h2>
            <canvas id="topProductsChart" height="220"></canvas>
        </div>
        <div class="bg-[#1C1E27] border border-white/10 rounded-2xl p-6">
            <h2 class="font-[Fraunces] text-lg mb-4">Top wilayas</h2>
            <canvas id="wilayaChart" height="220"></canvas>
        </div>
    </div>

    <script>
        Chart.defaults.color = '#9C9788';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';

        const range    = '{{ request('range', 'month') }}';
        const dateFrom = '{{ request('date_from') }}';
        const dateTo   = '{{ request('date_to') }}';
        const url      = `{{ route('admin.analytics.data') }}?range=${range}&date_from=${dateFrom}&date_to=${dateTo}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                new Chart(document.getElementById('monthlySalesChart'), {
                    type: 'bar',
                    data: {
                        labels: data.monthly_sales.map(d => d.label),
                        datasets: [{
                            label: 'Ventes (DA)',
                            data: data.monthly_sales.map(d => d.total),
                            backgroundColor: 'rgba(201,162,75,0.6)',
                            borderColor: '#C9A24B',
                            borderWidth: 1,
                            borderRadius: 6,
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });

                const statusColors = {
                    pending:'#eab308', processing:'#3b82f6', paid:'#22c55e',
                    shipped:'#6366f1', delivered:'#10b981', cancelled:'#ef4444', refunded:'#f97316'
                };
                const statusLabels = Object.keys(data.orders_by_status);
                new Chart(document.getElementById('orderStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusLabels.map(k => data.orders_by_status[k]),
                            backgroundColor: statusLabels.map(k => statusColors[k] || '#9C9788'),
                            borderWidth: 0,
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
                });

                new Chart(document.getElementById('topProductsChart'), {
                    type: 'bar',
                    data: {
                        labels: data.top_products.map(p => p.product_name),
                        datasets: [{
                            label: 'Qté vendue',
                            data: data.top_products.map(p => p.total_qty),
                            backgroundColor: 'rgba(99,102,241,0.6)',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            borderRadius: 6,
                        }]
                    },
                    options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
                });

                new Chart(document.getElementById('wilayaChart'), {
                    type: 'bar',
                    data: {
                        labels: data.by_wilaya.map(w => w.shipping_wilaya),
                        datasets: [{
                            label: 'Ventes (DA)',
                            data: data.by_wilaya.map(w => w.total),
                            backgroundColor: 'rgba(16,185,129,0.6)',
                            borderColor: '#10b981',
                            borderWidth: 1,
                            borderRadius: 6,
                        }]
                    },
                    options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
                });
            });
    </script>

@endsection