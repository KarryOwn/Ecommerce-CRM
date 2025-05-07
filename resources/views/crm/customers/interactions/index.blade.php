@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Customer Interactions</h1>
            <p class="text-gray-600">{{ $customer->first_name }} {{ $customer->last_name }}</p>
        </div>
        <a href="{{ route('customers.interactions.create', $customer) }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            New Interaction
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Priority</th>
                        <th class="px-4 py-2">Handler</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interactions as $interaction)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $interaction->created_at->format('M d, Y H:i') }}</td>
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
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm 
                                    @if($interaction->priority === 'urgent') bg-red-100 text-red-800
                                    @elseif($interaction->priority === 'high') bg-orange-100 text-orange-800
                                    @elseif($interaction->priority === 'medium') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($interaction->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $interaction->handler->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('customers.interactions.show', [$customer, $interaction]) }}" 
                                   class="text-blue-500 hover:text-blue-700">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                                No interactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $interactions->links() }}
        </div>
    </div>
</div>
@endsection