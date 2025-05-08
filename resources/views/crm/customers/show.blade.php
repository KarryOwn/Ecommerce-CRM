@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Customer Details</h1>
            <div class="flex gap-2">
                <a href="{{ route('customers.loyalty', $customer) }}" 
                   class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        View Loyalty Points
                    </span>
                </a>
                <a href="{{ route('customers.edit', $customer) }}" 
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                    Edit Customer
                </a>
                <a href="{{ route('customers.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Name</p>
                    <p class="font-semibold">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Email</p>
                    <p class="font-semibold">{{ $customer->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Phone</p>
                    <p class="font-semibold">{{ $customer->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Status</p>
                    <span class="px-2 py-1 rounded text-sm inline-block
                        @if($customer->status === 'active') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($customer->status) }}
                    </span>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600">Address</p>
                    <p class="font-semibold">
                        {{ $customer->address ?? 'N/A' }}
                        {{ $customer->city ? ", $customer->city" : '' }}
                        {{ $customer->state ? ", $customer->state" : '' }}
                        {{ $customer->postal_code ? " $customer->postal_code" : '' }}
                        {{ $customer->country ? ", $customer->country" : '' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Customer Activity -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-medium mb-4">Customer Activity</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Orders Section -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Recent Orders</h3>
                    @forelse($customer->orders()->latest()->take(5)->get() as $order)
                        <div class="border-b py-2">
                            <a href="{{ route('orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Order #{{ $order->order_number }}
                            </a>
                            <p class="text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y') }} - 
                                ${{ number_format($order->total_amount, 2) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">No orders found</p>
                    @endforelse
                    <div class="mt-2">
                        <a href="{{ route('orders.index', ['customer_id' => $customer->id]) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm">
                            View All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Interactions -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Recent Interactions</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <span class="text-gray-600">Latest interactions with customer</span>
                    <a href="{{ route('interactions.create', ['customer_id' => $customer->id]) }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        New Interaction
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Interactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Interactions History</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Subject</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Handled By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interactions as $interaction)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $interaction->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-2 capitalize">{{ $interaction->type }}</td>
                                <td class="px-4 py-2">{{ $interaction->subject }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-sm 
                                        @if($interaction->status === 'open') bg-yellow-100 text-yellow-800
                                        @elseif($interaction->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($interaction->status === 'resolved') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ str_replace('_', ' ', ucfirst($interaction->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $interaction->user->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                    No interactions found
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