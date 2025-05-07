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
        <div class="h-80">
            <canvas id="customerGrowthChart"></canvas>
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('customerGrowthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($customerGrowth->pluck('date')) !!},
            datasets: [{
                label: 'New Customers by Day',
                data: {!! json_encode($customerGrowth->pluck('count')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
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
</script>
@endpush
@endsection