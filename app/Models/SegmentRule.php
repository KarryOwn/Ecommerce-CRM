<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SegmentRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'segment_id',
        'field',
        'operator',
        'value',
        'condition_type',
        'group_id'
    ];

    public function segment()
    {
        return $this->belongsTo(CustomerSegment::class, 'segment_id');
    }
}
