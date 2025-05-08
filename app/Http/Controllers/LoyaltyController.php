<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTransaction;
use App\Models\LoyaltyTier;
use App\Models\Customer;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_members' => LoyaltyPoint::count(),
            'total_points' => LoyaltyPoint::sum('points'),
            'active_tiers' => 4
        ];

        // Get tier counts
        $tierCounts = [
            'bronze' => LoyaltyPoint::where('tier', 'bronze')->count(),
            'silver' => LoyaltyPoint::where('tier', 'silver')->count(),
            'gold' => LoyaltyPoint::where('tier', 'gold')->count(),
            'platinum' => LoyaltyPoint::where('tier', 'platinum')->count(),
        ];

        // Get recent transactions
        $recentTransactions = LoyaltyTransaction::with('customer')
            ->latest()
            ->take(10)
            ->get();

        return view('crm.loyalty.dashboard', compact('stats', 'tierCounts', 'recentTransactions'));
    }

    public function members()
    {
        $members = LoyaltyPoint::with(['customer', 'transactions'])
            ->paginate(20);

        return view('crm.loyalty.members', compact('members'));
    }

    public function memberDetails(Customer $customer)
    {
        $loyalty = $customer->loyaltyPoints()
            ->with(['transactions' => function($query) {
                $query->latest()->take(10);
            }])
            ->firstOrFail();

        return view('crm.loyalty.member-details', compact('customer', 'loyalty'));
    }

    public function adjustPoints(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer',
            'reason' => 'required|string|max:255'
        ]);

        $loyalty = $customer->loyaltyPoints()->firstOrCreate([
            'points' => 0,
            'lifetime_points' => 0,
            'tier' => 'bronze'
        ]);

        LoyaltyTransaction::create([
            'customer_id' => $customer->id,
            'points' => $validated['points'],
            'type' => 'adjustment',
            'source' => $validated['reason']
        ]);

        $loyalty->points += $validated['points'];
        $loyalty->lifetime_points += max(0, $validated['points']);
        $loyalty->save();

        return back()->with('success', 'Points adjusted successfully');
    }

    public function transactions()
    {
        $transactions = LoyaltyTransaction::with(['customer'])
            ->latest()
            ->paginate(20);

        return view('crm.loyalty.transactions', [
            'transactions' => $transactions
        ]);
    }

    public function showTransaction(LoyaltyTransaction $transaction)
    {
        return view('crm.loyalty.transactions.show', [
            'transaction' => $transaction->load(['customer', 'order'])
        ]);
    }
}
