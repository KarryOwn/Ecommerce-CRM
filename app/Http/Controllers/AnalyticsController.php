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
        // Calculate total sales
        $totalSales = Order::where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Calculate monthly sales
        $monthlySales = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        // Get customer growth data (last 30 days)
        $customerGrowth = Customer::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });

        // Get segment performance
        $segmentPerformance = CustomerSegment::withCount('customers')
            ->orderByDesc('customers_count')
            ->get();

        return view('crm.analytics.dashboard', compact(
            'totalSales',
            'monthlySales',
            'customerGrowth',
            'segmentPerformance'
        ));
    }
}
