<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\CustomerSegment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Get orders for the current month
        $orders = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->get();

        // Calculate customer growth for the last 30 days
        $customerGrowth = Customer::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Ensure we have data for all 30 days
        $dates = collect();
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $customerGrowth->where('date', $date)->first()?->count ?? 0;
            $dates->push([
                'date' => now()->subDays($i)->format('M d'),
                'count' => $count
            ]);
        }

        $customerGrowth = $dates;

        // Calculate metrics
        $totalSales = Order::where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $monthlySales = $orders->sum('total_amount');

        $segmentPerformance = CustomerSegment::withCount('customers')
            ->orderByDesc('customers_count')
            ->get();

        $buyerTrends = [
            'popular_products' => $orders
                ->flatMap(fn($order) => $order->items)
                ->groupBy('product_id')
                ->map(function($items) {
                    $first = $items->first();
                    return [
                        'product' => $first->product->name,
                        'quantity' => $items->sum('quantity'),
                        'revenue' => $items->sum('subtotal')
                    ];
                })
                ->sortByDesc('quantity')
                ->take(5)
                ->values(),

            'customer_segments' => CustomerSegment::withCount(['customers as orders_count' => function($query) {
                    $query->has('orders');
                }])
                ->withSum(['customers as total_revenue' => function($query) {
                    $query->join('orders', 'customers.id', '=', 'orders.customer_id')
                        ->where('orders.status', '!=', 'cancelled');
                }], 'orders.total_amount')
                ->get(),

            'peak_hours' => $orders
                ->groupBy(function($order) {
                    return $order->created_at->format('H');
                })
                ->map(fn($orders) => $orders->count())
                ->sortKeys(),

            'repeat_customers' => Customer::withCount('orders')
                ->having('orders_count', '>', 1)
                ->count(),
        ];

        return view('crm.analytics.dashboard', compact(
            'customerGrowth',
            'totalSales',
            'monthlySales',
            'segmentPerformance',
            'buyerTrends',
            'orders'  // Add orders to the view
        ));
    }
}
