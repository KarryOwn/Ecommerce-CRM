@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Segment Details</h1>
                <p class="text-gray-600 mt-1">Created {{ $segment->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('segmentation.edit', $segment) }}" 
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                    Edit Segment
                </a>
                <a href="{{ route('segmentation.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Segment Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Segment Information</h2>
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-600 text-sm">Name</span>
                            <p class="font-medium">{{ $segment->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Description</span>
                            <p class="mt-1">{{ $segment->description ?? 'No description provided' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Status</span>
                            <p>
                                <span class="px-2 py-1 text-xs rounded-full {{ $segment->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $segment->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Statistics</h2>
                <div class="space-y-4">
                    <div>
                        <span class="text-gray-600 text-sm">Total Customers</span>
                        <p class="text-3xl font-bold">{{ $customersCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Segment Criteria -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Segment Criteria</h2>
                    <div class="space-y-4">
                        @foreach($segment->criteria as $criterion)
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <span class="text-gray-600 text-sm">Field</span>
                                    <p class="font-medium">
                                        @if($criterion['field'] === 'loyalty_tier')
                                            Loyalty Tier
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $criterion['field'])) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <span class="text-gray-600 text-sm">Operator</span>
                                    <p class="font-medium">{{ $criterion['operator'] }}</p>
                                </div>
                                <div class="flex-1">
                                    <span class="text-gray-600 text-sm">Value</span>
                                    <p class="font-medium">
                                        @if($criterion['field'] === 'loyalty_tier')
                                            <span class="capitalize">{{ $criterion['value'] }}</span>
                                        @else
                                            {{ $criterion['value'] }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end gap-2">
            <form action="{{ route('segmentation.evaluate', $segment) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Re-evaluate Segment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection