@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Campaign</h1>
            <a href="{{ route('campaigns.show', $campaign) }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to Details
            </a>
        </div>

        <form action="{{ route('campaigns.update', $campaign) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Name</label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" 
                           class="w-full rounded-lg border-gray-300" required>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="w-full rounded-lg border-gray-300" required>
                            <option value="email" {{ $campaign->type === 'email' ? 'selected' : '' }}>Email</option>
                            <option value="sms" {{ $campaign->type === 'sms' ? 'selected' : '' }}>SMS</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Segment</label>
                        <select name="segment_id" class="w-full rounded-lg border-gray-300" required>
                            @foreach($segments as $segment)
                                <option value="{{ $segment->id }}" 
                                    {{ $campaign->segment_id === $segment->id ? 'selected' : '' }}>
                                    {{ $segment->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date</label>
                        <input type="datetime-local" name="scheduled_at" 
                               value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300" required>
                            <option value="pending" {{ $campaign->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ $campaign->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ $campaign->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full rounded-lg border-gray-300"
                              placeholder="Campaign description">{{ old('description', $campaign->description) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        Update Campaign
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection