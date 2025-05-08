<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\LoyaltyPoint;

class CustomerSegment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'criteria',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'criteria' => 'array'
    ];

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }

    protected function applyLoyaltyTierCriteria($query, $operator, $value)
    {
        $query->whereHas('loyaltyPoints', function ($query) use ($operator, $value) {
            $query->where('tier', $operator, $value);
        });
    }
}
