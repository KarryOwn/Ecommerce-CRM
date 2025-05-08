<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Customer;
use App\Jobs\SendCampaignEmail;
use Carbon\Carbon;

class CampaignService
{
    public function executeCampaign(Campaign $campaign)
    {
        $customers = $campaign->segment->customers;
        
        foreach ($customers as $customer) {
            match ($campaign->type) {
                'email' => $this->scheduleEmail($campaign, $customer),
                'sms' => $this->scheduleSMS($campaign, $customer),
                default => null
            };
        }

        $campaign->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    protected function scheduleEmail(Campaign $campaign, Customer $customer)
    {
        $delay = $campaign->trigger_delay ? now()->addMinutes($campaign->trigger_delay) : now();
        
        SendCampaignEmail::dispatch($campaign, $customer)
            ->delay($delay);
    }

    protected function scheduleSMS(Campaign $campaign, Customer $customer)
    {
        // Implement SMS sending logic
    }
}