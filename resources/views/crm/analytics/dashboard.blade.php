@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-6">Analytics Dashboard</h1>
    
    <!-- Sales Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800">Total Sales</h3>
            <p class="text-3xl font-bold text-green-600">${{ number_format($totalSales, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">All time sales</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800">Monthly Sales</h3>
            <p class="text-3xl font-bold text-blue-600">${{ number_format($monthlySales, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">Current month</p>
        </div>
    </div>

    <!-- Customer Growth Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Growth</h3>
        <div style="height: 400px;">
            <canvas id="customerGrowthChart"></canvas>
        </div>
    </div>

    <!-- Popular Products -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Selling Products</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-right">Quantity Sold</th>
                        <th class="px-4 py-2 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buyerTrends['popular_products'] as $product)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $product['product'] }}</td>
                        <td class="px-4 py-2 text-right">{{ $product['quantity'] }}</td>
                        <td class="px-4 py-2 text-right">${{ number_format($product['revenue'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sales by Hour Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales by Hour</h3>
        <div style="height: 300px;">
            <canvas id="peakHoursChart"></canvas>
        </div>
    </div>

    <!-- Segment Performance -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Segment Performance</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Segment</th>
                        <th class="px-4 py-2 text-right">Customer Count</th>
                        <th class="px-4 py-2 text-right">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalCustomers = $segmentPerformance->sum('customers_count') @endphp
                    @foreach($segmentPerformance as $segment)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $segment->name }}</td>
                        <td class="px-4 py-2 text-right">{{ $segment->customers_count }}</td>
                        <td class="px-4 py-2 text-right">
                            {{ number_format(($segment->customers_count / ($totalCustomers ?: 1)) * 100, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Segments Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Segment Performance</h3>
            <div class="space-y-6 max-h-96 overflow-y-auto">
                @foreach($buyerTrends['customer_segments'] as $segment)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium truncate max-w-[200px]" title="{{ $segment->name }}">
                            {{ $segment->name }}
                        </span>
                        <span class="text-sm text-gray-600 whitespace-nowrap">
                            ${{ number_format($segment->total_revenue ?? 0, 2) }}
                        </span>
                    </div>
                    <div class="relative h-2 bg-gray-200 rounded w-full">
                        @php
                            $percentage = min(($segment->orders_count / ($segment->customers_count ?: 1) * 100), 100);
                        @endphp
                        <div class="absolute h-2 bg-blue-500 rounded" 
                             style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-500">
                            {{ $segment->orders_count }} orders
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $segment->customers_count }} customers
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Engagement</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $buyerTrends['repeat_customers'] }}
                    </p>
                    <p class="text-sm text-gray-500">Repeat Customers</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-green-600">
                        {{ number_format($monthlySales / ($orders->count() ?: 1), 2) }}
                    </p>
                    <p class="text-sm text-gray-500">Avg. Order Value</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('customerGrowthChart');
        if (!ctx) {
            console.error('Canvas element not found');
            return;
        }

        const chartData = {
            labels: {!! json_encode(collect($customerGrowth)->pluck('date')) !!},
            datasets: [{
                label: 'New Customers by Day',
                data: {!! json_encode(collect($customerGrowth)->pluck('count')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            title: (tooltipItems) => {
                                return 'Date: ' + tooltipItems[0].label;
                            },
                            label: (context) => {
                                return 'New Customers: ' + context.raw;
                            }
                        }
                    }
                }
            }
        });

        const peakHoursCtx = document.getElementById('peakHoursChart').getContext('2d');
        new Chart(peakHoursCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($buyerTrends['peak_hours']->toArray())) !!},
                datasets: [{
                    label: 'Orders by Hour',
                    data: {!! json_encode(array_values($buyerTrends['peak_hours']->toArray())) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection