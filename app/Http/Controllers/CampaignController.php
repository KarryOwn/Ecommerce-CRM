<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CustomerSegment;
use App\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index()
    {
        $campaigns = Campaign::with('segment')
            ->latest()
            ->paginate(10);

        return view('crm.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $segments = CustomerSegment::all();
        return view('crm.campaigns.create', compact('segments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms',
            'segment_id' => 'required|exists:customer_segments,id',
            'scheduled_at' => 'nullable|date',
            'trigger_delay' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $campaign = \App\Models\Campaign::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'segment_id' => $validated['segment_id'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'trigger_delay' => $validated['trigger_delay'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'content' => $request->input('content', json_encode([])), // or just []
        ]);

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully!');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load('segment');
        return view('crm.campaigns.show', compact('campaign'));
    }

    public function execute(Campaign $campaign)
    {
        $this->campaignService->executeCampaign($campaign);
        
        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign execution started');
    }

    public function edit(Campaign $campaign)
    {
        $segments = CustomerSegment::all();
        return view('crm.campaigns.edit', [
            'campaign' => $campaign,
            'segments' => $segments
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms',
            'segment_id' => 'required|exists:customer_segments,id',
            'status' => 'required|in:pending,active,completed',
            'scheduled_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $campaign->update($validated);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully');
    }
}
