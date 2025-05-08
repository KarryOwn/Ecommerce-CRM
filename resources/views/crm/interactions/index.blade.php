@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Customer Interactions Dashboard</h1>
            <h1 class="text-2xl font-semibold">Interactions for {{ $customer->first_name }} {{ $customer->last_name }}</h1>
            <p class="text-gray-600">{{ $customer->email }}</p>
        </div>
        <div class="space-x-4">
            <a href="{{ route('customers.interactions.create', $customer) }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                New Interaction
            </a>
            <a href="{{ route('customers.show', $customer) }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to Customer
            </a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Pending Responses</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                <div class="divide-y">
                    @forelse($interactions as $interaction)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $interaction->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $interaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $interaction->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ ucfirst($interaction->status) }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $interaction->type }}</span>
                                    </div>
                                    <h3 class="font-medium mt-2">{{ $interaction->subject }}</h3>
                                    <p class="text-gray-600 mt-1">{{ $interaction->description }}</p>
                                    <div class="text-sm text-gray-500 mt-2">
                                        <span>Created by {{ $interaction->user?->name ?? 'Unknown User' }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $interaction->created_at->format('M d, Y h:ia') }}</span>
                                        @if($interaction->requires_followup)
                                            <span class="mx-2">•</span>
                                            <span class="text-yellow-600">Requires Follow-up</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('customers.interactions.show', [$customer, $interaction]) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            No interactions found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection