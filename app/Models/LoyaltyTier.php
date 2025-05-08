<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTier extends Model
{
    protected $fillable = [
        'name',
        'required_points',
        'multiplier',
        'benefits'
    ];

    protected $casts = [
        'benefits' => 'array'
    ];

    public function members()
    {
        return $this->hasMany(LoyaltyPoint::class, 'tier', 'name');
    }
}
