@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Order Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-600">{{ $order->created_at->format('F d, Y h:ia') }}</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('orders.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Back to Orders
                </a>
            </div>
        </div>

        <!-- Order Status Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-medium mb-4">Order Timeline</h2>
            <div class="space-y-4">
                @foreach($order->tracking_history as $history)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-2 h-2 rounded-full 
                            {{ $history['status'] === 'delivered' ? 'bg-green-500' : 'bg-blue-500' }}">
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">
                                {{ ucfirst($history['status']) }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($history['timestamp'])->format('F d, Y h:ia') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Update Status Form -->
            @if($order->status !== 'delivered')
            <form action="{{ route('orders.update-status', $order) }}" method="POST" class="mt-6">
                @csrf
                @method('PATCH')
                <div class="flex space-x-4">
                    <select name="status" class="rounded-lg border-gray-300">
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Update Status
                    </button>
                </div>
            </form>
            @endif
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-medium mb-4">Order Details</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Customer</h3>
                    <p>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                    <p>{{ $order->customer->email }}</p>
                    <p>{{ $order->customer->phone }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Shipping Address</h3>
                    <p class="whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium mb-4">Order Items</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-right">Quantity</th>
                        <th class="px-4 py-2 text-right">Price</th>
                        <th class="px-4 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->product->name }}</td>
                        <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-2 text-right">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="font-medium">
                        <td colspan="3" class="px-4 py-2 text-right">Total:</td>
                        <td class="px-4 py-2 text-right">${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection