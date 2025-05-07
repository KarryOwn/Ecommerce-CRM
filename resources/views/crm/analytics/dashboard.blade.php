@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-6">Analytics Dashboard</h1>
    
    <!-- Sales Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800">Total Sales</h3>
            <p class="text-3xl font-bold text-green-600">${{ number_format($totalSales, 2) }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800">Monthly Sales</h3>
            <p class="text-3xl font-bold text-blue-600">${{ number_format($monthlySales, 2) }}</p>
        </div>
    </div>

    <!-- Customer Growth Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Growth</h3>
        <canvas id="customerGrowthChart"></canvas>
    </div>

    <!-- Segment Performance -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Segment Performance</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Segment</th>
                        <th class="px-4 py-2">Customer Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($segmentPerformance as $segment)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $segment->name }}</td>
                        <td class="px-4 py-2">{{ $segment->customer_count }}</td>
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
            labels: {!! json_encode($customerGrowth->pluck('month')) !!},
            datasets: [{
                label: 'New Customers',
                data: {!! json_encode($customerGrowth->pluck('count')) !!},
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection