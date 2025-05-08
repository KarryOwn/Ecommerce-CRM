@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            @foreach(['bronze', 'silver', 'gold', 'platinum'] as $tier)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-2">{{ ucfirst($tier) }}</h3>
                <p class="text-3xl font-bold {{ $tier === 'bronze' ? 'text-orange-600' : '' }}
                                            {{ $tier === 'silver' ? 'text-gray-600' : '' }}
                                            {{ $tier === 'gold' ? 'text-yellow-600' : '' }}
                                            {{ $tier === 'platinum' ? 'text-purple-600' : '' }}">
                    {{ $tierCounts[$tier] ?? 0 }}
                </p>
                <p class="text-sm text-gray-500 mt-1">Members</p>
            </div>
            @endforeach
        </div>

        <!-- Program Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-2">Total Members</h3>
                <p class="text-3xl font-bold">{{ number_format($stats['total_members'] ?? 0) }}</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-2">Total Points</h3>
                <p class="text-3xl font-bold">{{ number_format($stats['total_points'] ?? 0) }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-2">Active Tiers</h3>
                <p class="text-3xl font-bold">{{ $stats['active_tiers'] ?? 4 }}</p>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Recent Point Transactions</h2>
                    <a href="{{ route('loyalty.transactions') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        View All
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4">
                                    @if($transaction->customer)
                                        <a href="{{ route('customers.show', $transaction->customer) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Deleted Customer</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $transaction->points >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ ucfirst($transaction->type) }}</td>
                                <td class="px-6 py-4">{{ $transaction->source }}</td>
                                <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No transactions found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection