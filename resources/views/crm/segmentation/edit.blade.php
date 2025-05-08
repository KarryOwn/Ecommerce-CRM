@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Segment</h1>
            <a href="{{ route('segmentation.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <form action="{{ route('segmentation.update', $segment) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Segment Name</label>
                    <input type="text" name="name" value="{{ old('name', $segment->name) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description', $segment->description) }}</textarea>
                </div>

                <!-- Criteria Section -->
                <div id="criteria-container">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Segment Criteria</h3>
                    @foreach($segment->criteria as $index => $criterion)
                    <div class="criteria-row grid grid-cols-3 gap-4 relative {{ $index > 0 ? 'mt-4' : '' }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Field</label>
                            <select name="criteria[{{ $index }}][field]" 
                                    class="mt-1 block w-full rounded-md border-gray-300 js-criteria-field">
                                @foreach([
                                    'first_name' => 'First Name',
                                    'last_name' => 'Last Name',
                                    'email' => 'Email',
                                    'city' => 'City',
                                    'country' => 'Country',
                                    'total_orders' => 'Total Orders',
                                    'lifetime_value' => 'Lifetime Value',
                                    'loyalty_tier' => 'Loyalty Tier'
                                ] as $value => $label)
                                    <option value="{{ $value }}" {{ $criterion['field'] === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Operator</label>
                            <select name="criteria[{{ $index }}][operator]" 
                                    class="mt-1 block w-full rounded-md border-gray-300 js-criteria-operator">
                                @foreach([
                                    '=' => '=',
                                    '!=' => '!=',
                                    '>' => '>',
                                    '>=' => '>=',
                                    '<' => '<',
                                    '<=' => '<=',
                                    'contains' => 'Contains',
                                    'starts_with' => 'Starts With'
                                ] as $value => $label)
                                    <option value="{{ $value }}" {{ $criterion['operator'] === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Value</label>
                            <input type="text" 
                                   name="criteria[{{ $index }}][value]" 
                                   value="{{ $criterion['value'] }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 js-criteria-value"
                                   placeholder="Enter value">
                        </div>
                        
                        <!-- Add delete button -->
                        <button type="button" 
                                onclick="deleteCriterion(this)"
                                class="absolute -right-6 top-8 text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
                
                <div class="flex items-center gap-4">
                    <button type="button" onclick="addCriteria()"
                            class="text-blue-500 hover:text-blue-700 flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Criterion
                    </button>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
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

// ...rest of the JavaScript code is the same as in create.blade.php...
</script>
@endpush
@endsection