<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'total_amount',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'notes',
        'tracking_number',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'unit_price', 'subtotal');
    }

    public function updateStatus($status)
    {
        $this->update([
            'status' => $status,
            'tracking_history' => array_merge($this->tracking_history ?? [], [
                [
                    'status' => $status,
                    'timestamp' => now()->toDateTimeString(),
                    'user_id' => auth()->id(),
                ]
            ])
        ]);
    }

    public function getTrackingHistoryAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
