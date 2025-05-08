<?php

namespace App\Providers;

use App\Events\CustomerUpdated;
use App\Listeners\EvaluateCustomerSegments;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\CustomerUpdated::class => [
            \App\Listeners\EvaluateCustomerSegments::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}