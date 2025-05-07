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

                <!-- Rule Groups -->
                <div id="rule-groups">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Segment Rules</h3>
                    <div class="rule-group mb-4" data-group="1">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="mb-2 flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="font-medium">Rule Group 1</span>
                                    <button type="button" 
                                            onclick="removeRuleGroup(1)" 
                                            class="ml-2 text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <button type="button" onclick="addRule(1)"
                                        class="text-blue-500 hover:text-blue-700 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Rule
                                </button>
                            </div>
                            <div class="rules-container space-y-4">
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
                                            <option value="starts_with">Starts With</option>
                                            <option value="ends_with">Ends With</option>
                                        </select>
                                    </div>
                                    <div class="flex space-x-2">
                                        <input type="text" name="criteria[0][value]" class="flex-1 rounded-lg border-gray-300" placeholder="Value">
                                        <button type="button" onclick="removeCriteria(this)" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addRuleGroup()"
                        class="text-blue-500 hover:text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Rule Group
                </button>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Create Segment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let ruleGroupCount = 1;
let ruleCount = 1;

function addRuleGroup() {
    ruleGroupCount++;
    const groupDiv = document.createElement('div');
    groupDiv.className = 'rule-group mb-4';
    groupDiv.dataset.group = ruleGroupCount;
    
    groupDiv.innerHTML = `
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="mb-2 flex justify-between items-center">
                <div class="flex items-center">
                    <span class="font-medium">Rule Group ${ruleGroupCount}</span>
                    <button type="button" 
                            onclick="removeRuleGroup(${ruleGroupCount})" 
                            class="ml-2 text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <button type="button" 
                        onclick="addRule(${ruleGroupCount})"
                        class="text-blue-500 hover:text-blue-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Rule
                </button>
            </div>
            <div class="rules-container space-y-4">
                ${createRuleHTML(ruleGroupCount)}
            </div>
        </div>
    `;
    
    document.getElementById('rule-groups').appendChild(groupDiv);
}

function addRule(groupId) {
    ruleCount++;
    const container = document.querySelector(`[data-group="${groupId}"] .rules-container`);
    const ruleDiv = document.createElement('div');
    ruleDiv.className = 'rule grid grid-cols-5 gap-4 items-center';
    ruleDiv.innerHTML = createRuleHTML(groupId);
    container.appendChild(ruleDiv);
}

function removeRule(button) {
    button.closest('.rule').remove();
}

function removeRuleGroup(groupId) {
    document.querySelector(`[data-group="${groupId}"]`).remove();
}

function removeCriteria(button) {
    button.closest('.criteria-row').remove();
}

function createRuleHTML(groupId) {
    return `
        <select name="rules[${ruleCount}][field]" class="rounded-lg border-gray-300">
            <option value="lifetime_value">Lifetime Value</option>
            <option value="total_orders">Total Orders</option>
            <option value="last_purchase_date">Last Purchase Date</option>
            <option value="customer_tier">Customer Tier</option>
            <option value="status">Status</option>
        </select>
        <select name="rules[${ruleCount}][operator]" class="rounded-lg border-gray-300">
            <option value="=">=</option>
            <option value=">">></option>
            <option value="<"><</option>
            <option value=">=">>=</option>
            <option value="<="><=</option>
            <option value="contains">Contains</option>
            <option value="starts_with">Starts With</option>
            <option value="ends_with">Ends With</option>
        </select>
        <input type="text" 
               name="rules[${ruleCount}][value]" 
               class="rounded-lg border-gray-300" 
               placeholder="Value">
        <select name="rules[${ruleCount}][condition_type]" class="rounded-lg border-gray-300">
            <option value="and">AND</option>
            <option value="or">OR</option>
        </select>
        <div class="flex justify-end">
            <button type="button" 
                    onclick="removeRule(this)"
                    class="text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <input type="hidden" name="rules[${ruleCount}][group_id]" value="${groupId}">
    `;
}
</script>
@endpush
@endsection