<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerSegment;
use Illuminate\Database\Eloquent\Builder;

class SegmentationService
{
    public function evaluateCustomerForSegment(Customer $customer, CustomerSegment $segment): bool
    {
        if (empty($segment->criteria)) {
            return false;
        }

        foreach ($segment->criteria as $criterion) {
            $value = $customer->{$criterion['field']};
            $matches = $this->evaluateCriterion($value, $criterion['operator'], $criterion['value']);
            
            if (!$matches) {
                return false;
            }
        }
        
        return true;
    }

    private function evaluateCriterion($customerValue, $operator, $criterionValue)
    {
        if ($customerValue === null) {
            return false;
        }

        switch ($operator) {
            case '=':
                return $customerValue == $criterionValue;
            case '>':
                return $customerValue > $criterionValue;
            case '<':
                return $customerValue < $criterionValue;
            case '>=':
                return $customerValue >= $criterionValue;
            case '<=':
                return $customerValue <= $criterionValue;
            case 'contains':
                return str_contains(strtolower((string)$customerValue), strtolower($criterionValue));
            case 'starts_with':
                return str_starts_with(strtolower((string)$customerValue), strtolower($criterionValue));
            case 'ends_with':
                return str_ends_with(strtolower((string)$customerValue), strtolower($criterionValue));
            default:
                return false;
        }
    }

    public function assignCustomersToSegment(CustomerSegment $segment)
    {
        $matchingCustomers = [];
        $customers = Customer::all();

        foreach ($customers as $customer) {
            if ($this->evaluateCustomerForSegment($customer, $segment)) {
                $matchingCustomers[] = $customer->id;
            }
        }

        // Sync the matching customers with the segment
        $segment->customers()->sync($matchingCustomers);
    }
}