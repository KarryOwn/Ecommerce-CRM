@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Create Segment</h1>
            <a href="{{ route('segmentation.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <form action="{{ route('segmentation.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Segment Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <!-- Criteria Section -->
                <div id="criteria-container">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Segment Criteria</h3>
                    <div class="criteria-row grid grid-cols-3 gap-4">
                        <div>
                            <select name="criteria[0][field]" class="rounded-lg border-gray-300">
                                <option value="lifetime_value">Lifetime Value</option>
                                <option value="total_orders">Total Orders</option>
                                <option value="last_purchase_date">Last Purchase Date</option>
                                <option value="customer_tier">Customer Tier</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                        <div>
                            <select name="criteria[0][operator]" class="rounded-lg border-gray-300">
                                <option value="=">=</option>
                                <option value=">">></option>
                                <option value="<"><</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                                <option value="contains">Contains</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="criteria[0][value]" 
                                   class="rounded-lg border-gray-300" 
                                   placeholder="Value">
                        </div>
                    </div>
                </div>
                
                <button type="button" onclick="addCriteria()"
                        class="text-blue-500 hover:text-blue-700">
                    + Add Criterion
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let criteriaCount = 1;

function addCriteria() {
    criteriaCount++;
    const container = document.getElementById('criteria-container');
    const criteriaRow = document.createElement('div');
    criteriaRow.className = 'criteria-row grid grid-cols-3 gap-4';
    criteriaRow.innerHTML = `
        <div>
            <select name="criteria[${criteriaCount}][field]" class="rounded-lg border-gray-300">
                <option value="lifetime_value">Lifetime Value</option>
                <option value="total_orders">Total Orders</option>
                <option value="last_purchase_date">Last Purchase Date</option>
                <option value="customer_tier">Customer Tier</option>
                <option value="status">Status</option>
            </select>
        </div>
        <div>
            <select name="criteria[${criteriaCount}][operator]" class="rounded-lg border-gray-300">
                <option value="=">=</option>
                <option value=">">></option>
                <option value="<"><</option>
                <option value=">=">>=</option>
                <option value="<="><=</option>
                <option value="contains">Contains</option>
            </select>
        </div>
        <div>
            <input type="text" name="criteria[${criteriaCount}][value]" 
                   class="rounded-lg border-gray-300" 
                   placeholder="Value">
        </div>
    `;
    container.appendChild(criteriaRow);
}
</script>
@endpush
@endsection