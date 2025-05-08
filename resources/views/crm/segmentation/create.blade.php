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
                    <div class="criteria-row grid grid-cols-3 gap-4 relative">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Field</label>
                            <select name="criteria[0][field]" 
                                    class="mt-1 block w-full rounded-md border-gray-300 js-criteria-field">
                                <option value="first_name">First Name</option>
                                <option value="last_name">Last Name</option>
                                <option value="email">Email</option>
                                <option value="city">City</option>
                                <option value="country">Country</option>
                                <option value="total_orders">Total Orders</option>
                                <option value="lifetime_value">Lifetime Value</option>
                                <option value="loyalty_tier">Loyalty Tier</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Operator</label>
                            <select name="criteria[0][operator]" 
                                    class="mt-1 block w-full rounded-md border-gray-300 js-criteria-operator">
                                <option value="=">=</option>
                                <option value="!=">!=</option>
                                <option value=">">&gt;</option>
                                <option value=">=">&gt;=</option>
                                <option value="<">&lt;</option>
                                <option value="<=">&lt;=</option>
                                <option value="contains">Contains</option>
                                <option value="starts_with">Starts With</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Value</label>
                            <input type="text" 
                                   name="criteria[0][value]" 
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
                        Create Segment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<datalist id="tier-options">
    <option value="bronze">
    <option value="silver">
    <option value="gold">
    <option value="platinum">
</datalist>

@push('scripts')
<script>
let criteriaCount = 1;

function addCriteria() {
    const container = document.getElementById('criteria-container');
    const criteriaRow = document.createElement('div');
    criteriaRow.className = 'criteria-row grid grid-cols-3 gap-4 relative mt-4';
    criteriaRow.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700">Field</label>
            <select name="criteria[${criteriaCount}][field]" 
                    class="mt-1 block w-full rounded-md border-gray-300 js-criteria-field">
                <option value="first_name">First Name</option>
                <option value="last_name">Last Name</option>
                <option value="email">Email</option>
                <option value="city">City</option>
                <option value="country">Country</option>
                <option value="total_orders">Total Orders</option>
                <option value="lifetime_value">Lifetime Value</option>
                <option value="loyalty_tier">Loyalty Tier</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Operator</label>
            <select name="criteria[${criteriaCount}][operator]" 
                    class="mt-1 block w-full rounded-md border-gray-300 js-criteria-operator">
                <option value="=">=</option>
                <option value="!=">!=</option>
                <option value=">">&gt;</option>
                <option value=">=">&gt;=</option>
                <option value="<">&lt;</option>
                <option value="<=">&lt;=</option>
                <option value="contains">Contains</option>
                <option value="starts_with">Starts With</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Value</label>
            <input type="text" 
                   name="criteria[${criteriaCount}][value]" 
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
    `;
    container.appendChild(criteriaRow);
    criteriaCount++;
}

function deleteCriterion(button) {
    const criteriaRow = button.closest('.criteria-row');
    if (document.querySelectorAll('.criteria-row').length > 1) {
        criteriaRow.remove();
        reindexCriteria();
    } else {
        alert('At least one criterion is required.');
    }
}

function reindexCriteria() {
    document.querySelectorAll('.criteria-row').forEach((row, index) => {
        row.querySelectorAll('[name^="criteria["]').forEach(input => {
            const name = input.name;
            input.name = name.replace(/criteria\[\d+\]/, `criteria[${index}]`);
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('change', function(e) {
        if (e.target.matches('.js-criteria-field')) {
            const row = e.target.closest('.grid');
            const operatorSelect = row.querySelector('.js-criteria-operator');
            const valueInput = row.querySelector('.js-criteria-value');
            
            // Reset operator options
            operatorSelect.innerHTML = '';
            
            switch(e.target.value) {
                case 'loyalty_tier':
                    // Only allow equals and not equals for tiers
                    addOption(operatorSelect, '=', '=');
                    addOption(operatorSelect, '!=', '!=');
                    valueInput.setAttribute('list', 'tier-options');
                    valueInput.placeholder = 'Enter tier (bronze, silver, gold, platinum)';
                    break;
                    
                case 'total_orders':
                case 'lifetime_value':
                    // Allow numeric comparisons
                    addOption(operatorSelect, '=', '=');
                    addOption(operatorSelect, '!=', '!=');
                    addOption(operatorSelect, '>', '>');
                    addOption(operatorSelect, '>=', '>=');
                    addOption(operatorSelect, '<', '<');
                    addOption(operatorSelect, '<=', '<=');
                    valueInput.type = 'number';
                    valueInput.step = e.target.value === 'lifetime_value' ? '0.01' : '1';
                    valueInput.placeholder = 'Enter number';
                    break;
                    
                default:
                    // Text fields
                    addOption(operatorSelect, '=', '=');
                    addOption(operatorSelect, '!=', '!=');
                    addOption(operatorSelect, 'contains', 'Contains');
                    addOption(operatorSelect, 'starts_with', 'Starts With');
                    valueInput.type = 'text';
                    valueInput.placeholder = 'Enter value';
            }
        }
    });
});

function addOption(select, value, text) {
    const option = document.createElement('option');
    option.value = value;
    option.textContent = text;
    select.appendChild(option);
}
</script>
@endpush
@endsection