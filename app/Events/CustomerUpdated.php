<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Foundation\Events\Dispatchable;

class CustomerUpdated
{
    use Dispatchable;

    public $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}