<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSegment;
use App\Services\SegmentationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    protected function validateCriteria($criteria)
    {
        foreach ($criteria as $criterion) {
            if ($criterion['field'] === 'loyalty_tier') {
                $validTiers = ['bronze', 'silver', 'gold', 'platinum'];
                if (!in_array(strtolower($criterion['value']), $validTiers)) {
                    throw ValidationException::withMessages([
                        'criteria' => ['Invalid loyalty tier value. Must be bronze, silver, gold, or platinum.']
                    ]);
                }
                if (!in_array($criterion['operator'], ['=', '!='])) {
                    throw ValidationException::withMessages([
                        'criteria' => ['Loyalty tier only supports = and != operators.']
                    ]);
                }
            }
            
            if (in_array($criterion['field'], ['total_orders', 'lifetime_value'])) {
                if (!is_numeric($criterion['value'])) {
                    throw ValidationException::withMessages([
                        'criteria' => ['Value must be numeric for ' . $criterion['field']]
                    ]);
                }
            }
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'criteria' => 'required|array'
        ]);

        $this->validateCriteria($validated['criteria']);

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

    public function evaluateAll()
    {
        $segments = CustomerSegment::where('is_active', true)->get();
        $customers = Customer::all();
        $segmentationService = app(SegmentationService::class);
        
        foreach ($segments as $segment) {
            // Clear existing assignments
            $segment->customers()->detach();
            
            // Evaluate each customer for this segment
            foreach ($customers as $customer) {
                if ($segmentationService->evaluateCustomerForSegment($customer, $segment)) {
                    $segment->customers()->attach($customer->id);
                }
            }
        }

        return redirect()->back()->with('success', 'All segments have been re-evaluated.');
    }

    private function processSegmentCriteria(CustomerSegment $segment)
    {
        $query = $this->buildQuery($segment->criteria);

        $customers = $query->get();
        $segment->customers()->sync($customers->pluck('id'));
    }

    protected function buildQuery($criteria)
    {
        $query = Customer::query();

        foreach ($criteria as $criterion) {
            $field = $criterion['field'];
            $operator = $criterion['operator'];
            $value = $criterion['value'];

            switch ($field) {
                case 'loyalty_tier':
                    $query->whereHas('loyaltyPoints', function ($subQuery) use ($operator, $value) {
                        // Ensure case-insensitive comparison
                        $subQuery->whereRaw('LOWER(tier) ' . $operator . ' ?', [strtolower($value)]);
                    });
                    break;

                case 'lifetime_value':
                    $query->where('lifetime_value', $operator, (float) $value);
                    break;

                case 'total_orders':
                    $query->where('total_orders', $operator, (int) $value);
                    break;

                default:
                    if ($operator === 'contains') {
                        $query->where($field, 'LIKE', "%{$value}%");
                    } elseif ($operator === 'starts_with') {
                        $query->where($field, 'LIKE', "{$value}%");
                    } else {
                        $query->where($field, $operator, $value);
                    }
            }
        }

        return $query;
    }

    public function show(CustomerSegment $segment)
    {
        return view('crm.segmentation.show', [
            'segment' => $segment->load('customers'),
            'customersCount' => $segment->customers()->count()
        ]);
    }
}
