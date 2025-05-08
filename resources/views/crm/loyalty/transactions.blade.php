@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Point Transactions</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
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
                                <td class="px-6 py-4">{{ ucfirst($transaction->source) }}</td>
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
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection