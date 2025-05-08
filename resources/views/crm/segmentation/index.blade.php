@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Customer Segments</h1>
        <div class="space-x-4">
            <form x-data="{ loading: false }" 
                  action="{{ route('segments.evaluate-all') }}" 
                  method="POST" 
                  class="inline"
                  @submit="loading = true">
                @csrf
                <button type="submit" 
                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 disabled:opacity-50"
                        :disabled="loading"
                        onclick="return confirm('Are you sure you want to re-evaluate all segments? This may take a while.')">
                    <span x-show="!loading">Evaluate All Segments</span>
                    <span x-show="loading">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </form>
            <a href="{{ route('segmentation.create') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Create Segment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($segments as $segment)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-semibold">{{ $segment->name }}</h2>
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    {{ $segment->customers_count }} customers
                </span>
            </div>
            <p class="text-gray-600 mb-4">{{ $segment->description }}</p>
            <div class="space-y-2">
                @foreach($segment->criteria as $criterion)
                <div class="text-sm">
                    <span class="font-medium">{{ ucfirst($criterion['field']) }}</span>
                    <span class="text-gray-500">{{ $criterion['operator'] }}</span>
                    <span class="font-medium">{{ $criterion['value'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4 flex space-x-2">
                <a href="{{ route('segmentation.edit', $segment) }}"
                   class="text-blue-500 hover:text-blue-700">Edit</a>
                <form action="{{ route('segmentation.destroy', $segment) }}" 
                      method="POST" 
                      class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-red-500 hover:text-red-700"
                            onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
                <form action="{{ route('segmentation.evaluate', $segment) }}" 
                      method="POST" 
                      class="inline-block">
                    @csrf
                    <button type="submit" 
                            class="text-blue-500 hover:text-blue-700">
                        Evaluate
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection