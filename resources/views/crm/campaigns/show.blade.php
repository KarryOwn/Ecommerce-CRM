@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Campaign Details</h1>
                <p class="text-gray-600 mt-1">Created {{ $campaign->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('campaigns.edit', $campaign) }}" 
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                    Edit Campaign
                </a>
                <a href="{{ route('campaigns.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Campaign Info Card -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Campaign Information</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600 text-sm">Campaign Name</span>
                                <p class="font-medium">{{ $campaign->name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">Type</span>
                                <p>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $campaign->type === 'email' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($campaign->type) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-600 text-sm">Description</span>
                            <p class="mt-1">{{ $campaign->description ?? 'No description provided' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600 text-sm">Status</span>
                                <p>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $campaign->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($campaign->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">Scheduled For</span>
                                <p class="font-medium">
                                    {{ $campaign->scheduled_at?->format('M d, Y H:i') ?? 'Not scheduled' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Segment Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Segment Information</h2>
                <div class="space-y-4">
                    <div>
                        <span class="text-gray-600 text-sm">Segment Name</span>
                        <p class="font-medium">{{ $campaign->segment->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm">Customers Count</span>
                        <p class="font-medium">{{ $campaign->segment->customers_count ?? 0 }}</p>
                    </div>
                    <a href="{{ route('segments.show', $campaign->segment) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        View Segment Details
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end gap-2">
            @if($campaign->status === 'pending')
                <form action="{{ route('campaigns.execute', $campaign) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Execute Campaign
                    </button>
                </form>
            @endif
            <form action="{{ route('campaigns.destroy', $campaign) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    Delete Campaign
                </button>
            </form>
        </div>
    </div>
</div>
@endsection