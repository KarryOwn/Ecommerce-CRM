<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function rules()
    {
        return $this->hasMany(SegmentRule::class, 'segment_id');
    }

    public function getCriteriaAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) ?? [] : (array)$value;
    }
}
