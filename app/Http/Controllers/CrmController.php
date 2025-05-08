<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerInteraction;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function dashboard()
    {
        // Get dashboard statistics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'active')->count();
        $recentInteractions = CustomerInteraction::with(['customer', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Pass data to the view
        return view('crm.dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'recentInteractions'
        ));
    }

    public function customersList(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $customers = Customer::withCount('interactions')
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('crm.customers.index', compact('customers'));
    }

    public function customerDetails(Customer $customer)
    {
        $interactions = $customer->interactions()->with('user')->latest()->get();
        return view('crm.customers.show', compact('customer', 'interactions'));
    }

    public function show(Customer $customer)
    {
        $interactions = $customer->interactions()
            ->with('user')
            ->latest()
            ->get();

        return view('crm.customers.show', compact('customer', 'interactions'));
    }

    public function createInteraction(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'type' => 'required|in:support,inquiry,complaint,feedback,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $customer->interactions()->create([
            ...$validated,
            'handled_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Interaction recorded successfully');
    }

    public function create()
    {
        return view('crm.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,blocked'
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully');
    }

    public function edit(Customer $customer)
    {
        return view('crm.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,blocked'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
    }

    public function loyalty(Customer $customer)
    {
        $loyalty = $customer->loyaltyPoints()->firstOrCreate(
            [],
            ['points' => 0, 'lifetime_points' => 0, 'tier' => 'bronze']
        );

        $transactions = $customer->loyaltyTransactions()
            ->latest()
            ->paginate(20);

        return view('crm.customers.loyalty', compact('customer', 'loyalty', 'transactions'));
    }
}
