<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'type',
        'subject',
        'description',
        'status',
        'priority',
        'channel',
        'resolution',
        'resolved_by',
        'resolved_at',
        'requires_followup',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'requires_followup' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
