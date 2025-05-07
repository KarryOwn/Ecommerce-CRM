<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product'])
            ->when($request->search, function($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('first_name', 'like', "%{$request->search}%")
                        ->orWhere('last_name', 'like', "%{$request->search}%");
                  });
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            });

        $orders = $query->latest()->paginate(10);

        return view('crm.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('status', 'active')->get();
        return view('crm.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'billing_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Calculate total amount before creating order
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $totalAmount += $product->price * $item['quantity'];
        }

        // Create order with total amount
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $validated['shipping_address'],
            'billing_address' => $validated['billing_address'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        // Create order items
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $subtotal = $product->price * $item['quantity'];

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            // Update product stock
            $product->decrement('stock_quantity', $item['quantity']);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,shipped,delivered,cancelled'
        ]);

        $order->updateStatus($validated['status']);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product']);
        
        return view('crm.orders.show', [
            'order' => $order
        ]);
    }
}
