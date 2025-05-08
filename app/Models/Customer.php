<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\CustomerUpdated;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
        'notes',
        'lifetime_value',
        'total_orders',
        'last_purchase_date',
        'acquisition_source',
        'preferred_communication',
        'interests',
        'customer_tier'
    ];

    protected $casts = [
        'lifetime_value' => 'decimal:2',
        'total_orders' => 'integer',
        'last_purchase_date' => 'date',
        'interests' => 'array'
    ];

    protected $dispatchesEvents = [
        'created' => CustomerUpdated::class,
        'updated' => CustomerUpdated::class
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function interactions()
    {
        return $this->hasMany(CustomerInteraction::class);
    }

    public function segments()
    {
        return $this->belongsToMany(CustomerSegment::class);
    }
}
