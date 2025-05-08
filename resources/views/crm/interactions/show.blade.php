@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Interaction Details</h1>
                <p class="text-gray-600">{{ $customer->first_name }} {{ $customer->last_name }}</p>
            </div>
            <div class="space-x-4">
                <a href="{{ route('customers.interactions.index', $customer) }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b">
                <div class="flex items-center gap-4 mb-4">
                    <span class="px-3 py-1 rounded-full text-sm
                        {{ $interaction->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $interaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $interaction->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ ucfirst($interaction->status) }}
                    </span>
                    <span class="text-gray-500">{{ ucfirst($interaction->type) }}</span>
                    @if($interaction->requires_followup)
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                            Requires Follow-up
                        </span>
                    @endif
                </div>

                <h2 class="text-xl font-semibold mb-2">{{ $interaction->subject }}</h2>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $interaction->description }}</p>

                <div class="mt-4 text-sm text-gray-500">
                    <span>Created by {{ $interaction->user?->name ?? 'Unknown User' }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $interaction->created_at->format('M d, Y h:ia') }}</span>
                </div>
            </div>

            <div class="p-6 bg-gray-50">
                <h3 class="text-lg font-semibold mb-4">Additional Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Priority</p>
                        <p class="mt-1">{{ ucfirst($interaction->priority) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Channel</p>
                        <p class="mt-1">{{ ucfirst($interaction->channel) }}</p>
                    </div>
                    @if($interaction->scheduled_at)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Scheduled For</p>
                        <p class="mt-1">{{ $interaction->scheduled_at->format('M d, Y h:ia') }}</p>
                    </div>
                    @endif
                    @if($interaction->follow_up_date)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Follow-up Date</p>
                        <p class="mt-1">{{ $interaction->follow_up_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>

                @if($interaction->notes)
                <div class="mt-6">
                    <p class="text-sm font-medium text-gray-500 mb-2">Notes</p>
                    <p class="whitespace-pre-wrap">{{ $interaction->notes }}</p>
                </div>
                @endif
            </div>

            @if($interaction->status !== 'completed')
                <div class="mt-6 p-6 bg-white rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Resolve Interaction</h3>
                    <form action="{{ route('customers.interactions.resolve', [$customer, $interaction]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Resolution Details
                            </label>
                            <textarea name="resolution" 
                                      rows="4" 
                                      class="w-full rounded-lg border-gray-300 @error('resolution') border-red-500 @enderror"
                                      placeholder="Describe how this interaction was resolved..."
                                      required>{{ old('resolution') }}</textarea>
                            @error('resolution')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                Mark as Resolved
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-6 p-6 bg-green-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-green-800">Resolution</h3>
                        <span class="text-sm text-green-600">
                            Resolved {{ $interaction->resolved_at->diffForHumans() }}
                            by {{ $interaction->resolver?->name ?? 'Unknown' }}
                        </span>
                    </div>
                    <p class="mt-2 text-gray-700">{{ $interaction->resolution }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection