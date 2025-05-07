<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\CustomerSegment;
use App\Services\SegmentationService;
use Illuminate\Console\Command;

class EvaluateSegments extends Command
{
    protected $signature = 'customers:evaluate-segments';
    protected $description = 'Evaluate all customers against all segments';

    public function handle(SegmentationService $segmentationService)
    {
        $customers = Customer::all();
        $segments = CustomerSegment::all();
        $bar = $this->output->createProgressBar(count($customers));

        $this->info('Starting segment evaluation...');

        foreach ($customers as $customer) {
            foreach ($segments as $segment) {
                if ($segmentationService->evaluateCustomerForSegment($customer, $segment)) {
                    $segment->customers()->syncWithoutDetaching([$customer->id]);
                } else {
                    $segment->customers()->detach($customer->id);
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nSegment evaluation completed!");
    }
}