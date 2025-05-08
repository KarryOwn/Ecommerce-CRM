<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'points',
        'lifetime_points',
        'tier',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function segment()
    {
        return $this->belongsTo(CustomerSegment::class, 'customer_id', 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(LoyaltyTransaction::class, 'customer_id', 'customer_id');
    }
}
