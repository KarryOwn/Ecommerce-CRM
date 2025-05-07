<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'subject',
        'description',
        'status',
        'priority',
        'handled_by',
        'scheduled_at',
        'completed_at',
        'follow_up_date',
        'channel',
        'tags',
        'attachments',
        'notes'  // Add this line
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'follow_up_date' => 'datetime',
        'tags' => 'array',
        'attachments' => 'array'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function handler()
    {
        return $this->user();
    }
}
