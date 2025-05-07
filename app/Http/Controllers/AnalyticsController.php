<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Sales Analytics
        $totalSales = Sale::where('status', 'completed')->sum('amount');
        $monthlySales = Sale::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        // Customer Analytics
        $customerGrowth = Customer::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // Segment Performance
        $segmentPerformance = DB::table('customer_segments')
            ->leftJoin('customer_customer_segment', 'customer_segments.id', '=', 'customer_customer_segment.customer_segment_id')
            ->leftJoin('customers', 'customer_customer_segment.customer_id', '=', 'customers.id')
            ->select('customer_segments.name', DB::raw('COUNT(DISTINCT customers.id) as customer_count'))
            ->groupBy('customer_segments.id', 'customer_segments.name')
            ->get();

        return view('crm.analytics.dashboard', compact(
            'totalSales',
            'monthlySales',
            'customerGrowth',
            'segmentPerformance'
        ));
    }
}
