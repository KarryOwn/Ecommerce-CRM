@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Segment</h1>
            <a href="{{ route('segmentation.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <form action="{{ route('segmentation.update', ['segment' => $segment]) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Segment Name</label>
                    <input type="text" name="name" value="{{ old('name', $segment->name) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description', $segment->description) }}</textarea>
                </div>

                <!-- Segment Criteria -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Segment Criteria</h3>
                    <div id="criteria-container" class="space-y-4">
                        @foreach($segment->criteria as $index => $criterion)
                            <div class="criteria-row grid grid-cols-3 gap-4">
                                <div>
                                    <select name="criteria[{{ $index }}][field]" 
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="lifetime_value" {{ $criterion['field'] === 'lifetime_value' ? 'selected' : '' }}>
                                            Lifetime Value
                                        </option>
                                        <option value="total_orders" {{ $criterion['field'] === 'total_orders' ? 'selected' : '' }}>
                                            Total Orders
                                        </option>
                                        <option value="last_purchase_date" {{ $criterion['field'] === 'last_purchase_date' ? 'selected' : '' }}>
                                            Last Purchase Date
                                        </option>
                                        <option value="customer_tier" {{ $criterion['field'] === 'customer_tier' ? 'selected' : '' }}>
                                            Customer Tier
                                        </option>
                                        <option value="status" {{ $criterion['field'] === 'status' ? 'selected' : '' }}>
                                            Status
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <select name="criteria[{{ $index }}][operator]" 
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="=" {{ $criterion['operator'] === '=' ? 'selected' : '' }}>=</option>
                                        <option value=">" {{ $criterion['operator'] === '>' ? 'selected' : '' }}>></option>
                                        <option value="<" {{ $criterion['operator'] === '<' ? 'selected' : '' }}><</option>
                                        <option value=">=" {{ $criterion['operator'] === '>=' ? 'selected' : '' }}>>=</option>
                                        <option value="<=" {{ $criterion['operator'] === '<=' ? 'selected' : '' }}><=</option>
                                    </select>
                                </div>
                                <div class="flex space-x-2">
                                    <input type="text" name="criteria[{{ $index }}][value]"
                                           value="{{ $criterion['value'] }}"
                                           class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Value">
                                    <button type="button" 
                                            onclick="removeCriteria(this)"
                                            class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" 
                            onclick="addCriteria()"
                            class="mt-4 text-blue-500 hover:text-blue-700 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Criterion
                    </button>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Update Segment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let criteriaCount = {{ count($segment->criteria) }};

// ... rest of the JavaScript code remains the same as in create.blade.php ...
</script>
@endpush
@endsection