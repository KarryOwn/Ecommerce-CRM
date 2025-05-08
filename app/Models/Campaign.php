<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'segment_id',
        'scheduled_at',
        'trigger_delay',
        'description',
        'status',
    ];

    protected $casts = [
        'content' => 'array',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function segment()
    {
        return $this->belongsTo(CustomerSegment::class);
    }
}
