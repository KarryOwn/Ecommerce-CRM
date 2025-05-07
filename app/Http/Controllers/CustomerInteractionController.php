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
            ->with('handler')
            ->latest()
            ->paginate(10);

        return view('crm.customers.interactions.index', compact('customer', 'interactions'));
    }

    public function create(Customer $customer)
    {
        return view('crm.customers.interactions.create', compact('customer'));
    }

    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'type' => 'required|in:support,inquiry,complaint,feedback,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'channel' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'follow_up_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('interactions');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $validated['handled_by'] = auth()->id();
        
        $interaction = $customer->interactions()->create($validated);

        return redirect()->route('customers.interactions.index', $customer)
            ->with('success', 'Interaction recorded successfully');
    }

    public function show(Customer $customer, CustomerInteraction $interaction)
    {
        return view('crm.customers.interactions.show', compact('customer', 'interaction'));
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
}
