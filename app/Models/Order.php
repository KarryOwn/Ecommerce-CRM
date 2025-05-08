<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\LoyaltyService;

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

    public function calculateLoyaltyPoints()
    {
        // Basic calculation: 1 point per $1 spent
        return (int) $this->total_amount;
    }

    protected static function booted()
    {
        static::created(function ($order) {
            $loyaltyService = new LoyaltyService();
            
            // Award points based on order total (1 point per dollar)
            $points = (int) $order->total_amount;
            
            $loyaltyService->addPoints(
                $order->customer_id,
                $points,
                'order_completed',
                'Order #' . $order->id
            );
        });
    }
}
