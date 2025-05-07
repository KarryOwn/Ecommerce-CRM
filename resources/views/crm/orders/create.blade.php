@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Create Order</h1>
            <a href="{{ route('orders.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            <div class="space-y-6">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                    <select name="customer_id" class="w-full rounded-lg border-gray-300" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Order Items -->
                <div>
                    <h2 class="text-lg font-medium mb-4">Order Items</h2>
                    <div id="order-items" class="space-y-4">
                        <div class="order-item grid grid-cols-4 gap-4">
                            <select name="items[0][product_id]" class="rounded-lg border-gray-300" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }} (${{ number_format($product->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="items[0][quantity]" 
                                   class="rounded-lg border-gray-300"
                                   min="1" value="1" required
                                   placeholder="Quantity">
                            <div class="col-span-2 flex items-center justify-between">
                                <span class="item-subtotal">$0.00</span>
                                <button type="button" onclick="removeOrderItem(this)"
                                        class="text-red-500 hover:text-red-700">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addOrderItem()"
                            class="mt-4 text-blue-500 hover:text-blue-700">
                        + Add Another Item
                    </button>
                </div>

                <!-- Shipping Details -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                        <textarea name="shipping_address" rows="3"
                                  class="w-full rounded-lg border-gray-300" required>{{ old('shipping_address') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                        <textarea name="billing_address" rows="3"
                                  class="w-full rounded-lg border-gray-300" required>{{ old('billing_address') }}</textarea>
                    </div>
                </div>

                <!-- Payment Details -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" class="rounded-lg border-gray-300" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-lg border-gray-300">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Create Order
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemCount = 1;

function addOrderItem() {
    const template = `
        <div class="order-item grid grid-cols-4 gap-4">
            <select name="items[${itemCount}][product_id]" class="rounded-lg border-gray-300" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }} (${{ number_format($product->price, 2) }})
                    </option>
                @endforeach
            </select>
            <input type="number" name="items[${itemCount}][quantity]" 
                   class="rounded-lg border-gray-300"
                   min="1" value="1" required
                   placeholder="Quantity">
            <div class="col-span-2 flex items-center justify-between">
                <span class="item-subtotal">$0.00</span>
                <button type="button" onclick="removeOrderItem(this)"
                        class="text-red-500 hover:text-red-700">
                    Remove
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('order-items').insertAdjacentHTML('beforeend', template);
    itemCount++;
}

function removeOrderItem(button) {
    button.closest('.order-item').remove();
}

// Add event listeners for calculating subtotals
document.addEventListener('change', function(e) {
    if (e.target.matches('select[name*="product_id"]') || e.target.matches('input[name*="quantity"]')) {
        const item = e.target.closest('.order-item');
        const select = item.querySelector('select');
        const quantity = item.querySelector('input[type="number"]').value;
        const price = select.options[select.selectedIndex].dataset.price;
        const subtotal = (price * quantity).toFixed(2);
        item.querySelector('.item-subtotal').textContent = `$${subtotal}`;
    }
});
</script>
@endpush
@endsection