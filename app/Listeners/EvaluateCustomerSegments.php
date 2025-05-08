<?php

namespace App\Listeners;

use App\Events\CustomerUpdated;
use App\Models\CustomerSegment;
use App\Services\SegmentationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class EvaluateCustomerSegments implements ShouldQueue
{
    protected $segmentationService;

    public function __construct(SegmentationService $segmentationService)
    {
        $this->segmentationService = $segmentationService;
    }

    public function handle(CustomerUpdated $event)
    {
        $customer = $event->customer;
        $segments = CustomerSegment::where('is_active', true)->get();

        foreach ($segments as $segment) {
            if ($this->segmentationService->evaluateCustomerForSegment($customer, $segment)) {
                $segment->customers()->syncWithoutDetaching([$customer->id]);
            } else {
                $segment->customers()->detach($customer->id);
            }
        }
    }
}