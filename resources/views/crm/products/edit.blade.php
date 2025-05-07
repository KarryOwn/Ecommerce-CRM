@extends('layouts.app')

@section('main-content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Product</h1>
            <a href="{{ route('products.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                           class="w-full rounded-lg border-gray-300 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                           class="w-full rounded-lg border-gray-300 @error('sku') border-red-500 @enderror"
                           required>
                    @error('sku')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border-gray-300">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}"
                           step="0.01" min="0"
                           class="w-full rounded-lg border-gray-300"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost ($)</label>
                    <input type="number" name="cost" value="{{ old('cost', $product->cost) }}"
                           step="0.01" min="0"
                           class="w-full rounded-lg border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity" 
                           value="{{ old('stock_quantity', $product->stock_quantity) }}"
                           min="0"
                           class="w-full rounded-lg border-gray-300"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" name="category" value="{{ old('category', $product->category) }}"
                           class="w-full rounded-lg border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300">
                        <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="out_of_stock" {{ $product->status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection