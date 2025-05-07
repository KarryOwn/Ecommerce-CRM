<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSegment;
use App\Services\SegmentationService;
use Illuminate\Http\Request;

class CustomerSegmentationController extends Controller
{
    protected $segmentationService;

    public function __construct(SegmentationService $segmentationService)
    {
        $this->segmentationService = $segmentationService;
    }

    public function index()
    {
        $segments = CustomerSegment::withCount('customers')->get();
        return view('crm.segmentation.index', compact('segments'));
    }

    public function create()
    {
        $conditions = [
            'lifetime_value' => ['operator' => ['>', '<', '=', '>=', '<=']],
            'total_orders' => ['operator' => ['>', '<', '=', '>=', '<=']],
            'last_purchase_date' => ['operator' => ['>', '<', '=', 'between']],
            'customer_tier' => ['operator' => ['='], 'values' => ['standard', 'silver', 'gold', 'platinum']],
            'status' => ['operator' => ['='], 'values' => ['active', 'inactive', 'blocked']]
        ];

        return view('crm.segmentation.create', compact('conditions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'criteria' => 'nullable|array',
            'criteria.*.field' => 'required|string',
            'criteria.*.operator' => 'required|string',
            'criteria.*.value' => 'required|string',
        ]);

        $segment = CustomerSegment::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'criteria' => $request->input('criteria', []),
            'is_active' => true
        ]);

        // Evaluate customers for the new segment
        $this->segmentationService->assignCustomersToSegment($segment);

        return redirect()->route('segmentation.index')
            ->with('success', 'Segment created and evaluated successfully');
    }

    public function edit(CustomerSegment $segment)
    {
        return view('crm.segmentation.edit', compact('segment'));
    }

    public function update(Request $request, CustomerSegment $segment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'criteria' => 'nullable|array',
            'criteria.*.field' => 'required|string',
            'criteria.*.operator' => 'required|string',
            'criteria.*.value' => 'required|string',
        ]);

        $segment->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'criteria' => $request->input('criteria', [])
        ]);

        return redirect()->route('segmentation.index')
            ->with('success', 'Segment updated successfully');
    }

    public function destroy(CustomerSegment $segment)
    {
        // Remove all customer associations
        $segment->customers()->detach();
        
        // Delete the segment
        $segment->delete();

        return redirect()->route('segmentation.index')
            ->with('success', 'Segment deleted successfully');
    }

    public function evaluateSegment(CustomerSegment $segment)
    {
        $this->segmentationService->assignCustomersToSegment($segment);
        
        return redirect()->back()
            ->with('success', 'Segment evaluation completed');
    }

    private function processSegmentCriteria(CustomerSegment $segment)
    {
        $query = Customer::query();

        foreach ($segment->criteria as $criterion) {
            $field = $criterion['field'];
            $operator = $criterion['operator'];
            $value = $criterion['value'];

            switch ($operator) {
                case 'between':
                    $values = explode(',', $value);
                    $query->whereBetween($field, $values);
                    break;
                default:
                    $query->where($field, $operator, $value);
            }
        }

        $customers = $query->get();
        $segment->customers()->sync($customers->pluck('id'));
    }
}
