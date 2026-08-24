<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        return view('admin.analytics');
    }

    public function data(): JsonResponse
{
    $range    = request('range', 'month');
    $dateFrom = request('date_from');
    $dateTo   = request('date_to');

    // حساب الـ date range
    $from = match($range) {
        'today' => now()->startOfDay(),
        'week'  => now()->subDays(7)->startOfDay(),
        'year'  => now()->subYear()->startOfDay(),
        'custom'=> $dateFrom ? \Carbon\Carbon::parse($dateFrom)->startOfDay() : now()->subMonth()->startOfDay(),
        default => now()->subMonth()->startOfDay(), // month
    };

    $to = $range === 'custom' && $dateTo
        ? \Carbon\Carbon::parse($dateTo)->endOfDay()
        : now()->endOfDay();

    // مبيعات
    $monthlySales = \App\Models\Order::where('status', 'paid')
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->map(fn($item) => [
            'label' => \Carbon\Carbon::parse($item->date)->format('d/m'),
            'total' => (float) $item->total,
            'count' => $item->count,
        ]);

    // Top produits
    $topProducts = \App\Models\OrderItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(total) as total_revenue')
        ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$from, $to]))
        ->groupBy('product_name')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();

    // Statuts
    $ordersByStatus = \App\Models\Order::whereBetween('created_at', [$from, $to])
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->get()
        ->mapWithKeys(fn($item) => [$item->status => $item->count]);

    // Wilayas
    $byWilaya = \App\Models\Order::where('status', 'paid')
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('shipping_wilaya, SUM(total) as total, COUNT(*) as count')
        ->groupBy('shipping_wilaya')
        ->orderByDesc('total')
        ->take(10)
        ->get();

    return response()->json([
        'monthly_sales'    => $monthlySales,
        'top_products'     => $topProducts,
        'orders_by_status' => $ordersByStatus,
        'by_wilaya'        => $byWilaya,
        'period'           => ['from' => $from->format('d/m/Y'), 'to' => $to->format('d/m/Y')],
    ]);
}
}