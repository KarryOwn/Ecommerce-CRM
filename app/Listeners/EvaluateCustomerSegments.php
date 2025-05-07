<?php

namespace App\Listeners;

use App\Events\CustomerUpdated;
use App\Models\CustomerSegment;
use App\Services\SegmentationService;

class EvaluateCustomerSegments
{
    protected $segmentationService;

    public function __construct(SegmentationService $segmentationService)
    {
        $this->segmentationService = $segmentationService;
    }

    public function handle(CustomerUpdated $event)
    {
        $customer = $event->customer;
        $segments = CustomerSegment::all();

        foreach ($segments as $segment) {
            if ($this->segmentationService->evaluateCustomerForSegment($customer, $segment)) {
                $segment->customers()->syncWithoutDetaching([$customer->id]);
            } else {
                $segment->customers()->detach($customer->id);
            }
        }
    }
}