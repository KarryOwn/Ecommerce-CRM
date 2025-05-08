@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Transaction Details</h1>
            <a href="{{ route('loyalty.transactions') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to Transactions
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                    <dd class="mt-1">
                        <a href="{{ route('customers.show', $transaction->customer) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
                        </a>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Points</dt>
                    <dd class="mt-1">
                        <span class="{{ $transaction->points >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1">{{ ucfirst($transaction->type) }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                    <dd class="mt-1">{{ ucfirst($transaction->source) }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                    <dd class="mt-1">{{ $transaction->created_at->format('M d, Y H:i') }}</dd>
                </div>

                @if($transaction->order)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Related Order</dt>
                    <dd class="mt-1">
                        <a href="{{ route('orders.show', $transaction->order) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            View Order
                        </a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection