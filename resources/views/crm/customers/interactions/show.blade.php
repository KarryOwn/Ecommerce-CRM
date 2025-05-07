@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Interaction Details</h1>
                <p class="text-gray-600">{{ $customer->first_name }} {{ $customer->last_name }}</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('customers.interactions.index', $customer) }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <!-- Status and Priority Badges -->
            <div class="flex justify-between mb-6">
                <span class="px-3 py-1 rounded-full text-sm
                    @if($interaction->status === 'open') bg-yellow-100 text-yellow-800
                    @elseif($interaction->status === 'in_progress') bg-blue-100 text-blue-800
                    @elseif($interaction->status === 'resolved') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800 @endif">
                    Status: {{ str_replace('_', ' ', ucfirst($interaction->status)) }}
                </span>
                <span class="px-3 py-1 rounded-full text-sm
                    @if($interaction->priority === 'urgent') bg-red-100 text-red-800
                    @elseif($interaction->priority === 'high') bg-orange-100 text-orange-800
                    @elseif($interaction->priority === 'medium') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    Priority: {{ ucfirst($interaction->priority) }}
                </span>
            </div>

            <!-- Interaction Details -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Type</h3>
                    <p class="mt-1 capitalize">{{ $interaction->type }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Channel</h3>
                    <p class="mt-1 capitalize">{{ str_replace('_', ' ', $interaction->channel) }}</p>
                </div>

                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-500">Subject</h3>
                    <p class="mt-1">{{ $interaction->subject }}</p>
                </div>

                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-500">Description</h3>
                    <p class="mt-1 whitespace-pre-wrap">{{ $interaction->description }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Created By</h3>
                    <p class="mt-1">{{ $interaction->handler->name }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                    <p class="mt-1">{{ $interaction->created_at->format('M d, Y H:i') }}</p>
                </div>

                @if($interaction->scheduled_at)
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Scheduled For</h3>
                    <p class="mt-1">{{ $interaction->scheduled_at->format('M d, Y H:i') }}</p>
                </div>
                @endif

                @if($interaction->follow_up_date)
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Follow-up Date</h3>
                    <p class="mt-1">{{ $interaction->follow_up_date->format('M d, Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Attachments -->
            @if($interaction->attachments && count($interaction->attachments) > 0)
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Attachments</h3>
                <div class="space-y-2">
                    @foreach($interaction->attachments as $index => $attachment)
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        <a href="{{ route('customers.interactions.download', [$customer, $interaction, $index]) }}"
                           class="text-blue-500 hover:text-blue-700">
                            {{ $attachment['name'] }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Update Status Form -->
        <form action="{{ route('customers.interactions.update', [$customer, $interaction]) }}" 
              method="POST" 
              class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PUT')
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="open" {{ $interaction->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $interaction->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $interaction->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $interaction->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                    <input type="date" name="follow_up_date"
                           value="{{ $interaction->follow_up_date?->format('Y-m-d') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Update Interaction
                </button>
            </div>
        </form>
    </div>
</div>
@endsection