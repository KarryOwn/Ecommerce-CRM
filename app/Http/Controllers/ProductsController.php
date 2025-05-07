<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            })
            ->when($request->category, function($q) use ($request) {
                $q->where('category', $request->category);
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            });

        $products = $query->latest()->paginate(10);
        $categories = Product::distinct('category')->pluck('category')->filter();

        return view('crm.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('crm.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'status' => 'required|in:active,inactive,out_of_stock',
            'attributes' => 'nullable|array',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('crm.products.edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'status' => 'required|in:active,inactive,out_of_stock',
            'attributes' => 'nullable|array',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment' => 'required|integer',
            'type' => 'required|in:add,subtract',
        ]);

        if ($validated['type'] === 'add') {
            $product->increment('stock_quantity', $validated['adjustment']);
        } else {
            $product->decrement('stock_quantity', $validated['adjustment']);
        }

        if ($product->stock_quantity <= 0) {
            $product->update(['status' => 'out_of_stock']);
        }

        return redirect()->back()->with('success', 'Stock updated successfully');
    }
}
