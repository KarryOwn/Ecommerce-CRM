<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerInteractionController extends Controller
{
    public function index(Customer $customer)
    {
        $interactions = $customer->interactions()
            ->with(['user', 'customer']) // Eager load relationships
            ->latest()
            ->paginate(10);

        return view('crm.interactions.index', compact('customer', 'interactions'));
    }

    public function create(Customer $customer)
    {
        return view('crm.interactions.create', compact('customer'));
    }

    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'type' => 'required|in:support,inquiry,complaint,feedback,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed',
            'channel' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'follow_up_date' => 'nullable|date'
        ]);

        try {
            $interaction = $customer->interactions()->create([
                ...$validated,
                'user_id' => auth()->id(),
                'requires_followup' => $request->has('requires_followup')
            ]);

            return redirect()
                ->route('customers.interactions.index', $customer)
                ->with('success', 'Interaction created successfully.');
        } catch (\Exception $e) {
            \Log::error('Interaction creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create interaction.']);
        }
    }

    public function show(Customer $customer, CustomerInteraction $interaction)
    {
        $interaction->load(['user', 'customer']); // Eager load relationships
        return view('crm.interactions.show', compact('customer', 'interaction'));
    }

    public function update(Request $request, Customer $customer, CustomerInteraction $interaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date'
        ]);

        if ($request->status === 'closed') {
            $validated['completed_at'] = now();
        }

        $interaction->update($validated);

        return redirect()->route('customers.interactions.show', [$customer, $interaction])
            ->with('success', 'Interaction updated successfully');
    }

    public function downloadAttachment(Customer $customer, CustomerInteraction $interaction, $attachmentIndex)
    {
        $attachments = $interaction->attachments;
        if (!isset($attachments[$attachmentIndex])) {
            abort(404);
        }

        $attachment = $attachments[$attachmentIndex];
        return Storage::download($attachment['path'], $attachment['name']);
    }

    public function dashboard()
    {
        // Get pending and follow-up interactions
        $pendingInteractions = CustomerInteraction::with(['customer', 'user'])
            ->where('status', 'pending')
            ->orWhere('requires_followup', true)
            ->latest()
            ->get()
            ->groupBy('customer_id');

        // Get recent interactions for the timeline
        $recentInteractions = CustomerInteraction::with(['customer', 'user'])
            ->latest()
            ->take(20)
            ->get();

        // Get interaction statistics
        $stats = [
            'pending' => CustomerInteraction::where('status', 'pending')->count(),
            'in_progress' => CustomerInteraction::where('status', 'in_progress')->count(),
            'completed' => CustomerInteraction::where('status', 'completed')->count(),
            'followup' => CustomerInteraction::where('requires_followup', true)->count(),
        ];

        return view('crm.interactions.dashboard', compact(
            'pendingInteractions',
            'recentInteractions',
            'stats'
        ));
    }

    public function resolve(Request $request, Customer $customer, CustomerInteraction $interaction)
    {
        $validated = $request->validate([
            'resolution' => 'required|string|min:10',
        ]);

        $interaction->update([
            'resolution' => $validated['resolution'],
            'status' => 'completed',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'requires_followup' => false
        ]);

        return redirect()
            ->route('customers.interactions.show', [$customer, $interaction])
            ->with('success', 'Interaction resolved successfully.');
    }
}
