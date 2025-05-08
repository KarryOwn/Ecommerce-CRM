@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Customer Info -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold mb-2">{{ $customer->full_name }}</h1>
            <p class="text-gray-600">Loyalty Program Details</p>
        </div>

        <!-- Points Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-2">Current Points</h3>
                <p class="text-3xl font-bold">{{ number_format($loyalty->points) }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-2">Lifetime Points</h3>
                <p class="text-3xl font-bold">{{ number_format($loyalty->lifetime_points) }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium mb-2">Current Tier</h3>
                <p class="text-3xl font-bold capitalize">{{ $loyalty->tier }}</p>
            </div>
        </div>

        <!-- Point Transactions -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Points History</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $transaction->points >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ ucfirst($transaction->type) }}</td>
                            <td class="px-6 py-4">{{ ucfirst($transaction->source) }}</td>
                            <td class="px-6 py-4">
                                @if($transaction->order_id)
                                    <a href="{{ route('orders.show', $transaction->order_id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        View Order
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
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
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection