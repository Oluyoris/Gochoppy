<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'vendor_id',
        'dispatcher_id',
        'status',
        'payment_method',
        'payment_reference',
        'payment_status',
        'items_total',
        'delivery_fee',
        'service_charge',
        'grand_total',
        'estimated_delivery_time',
        'actual_delivery_time',
        'customer_address',
        'vendor_address',
        'notes',
        'delivery_code',
        'payment_proof',
        'phone',

        // NEW FIELDS FOR BUS STOP SYSTEM
        'user_bus_stop_id',
        'vendor_bus_stop_id',

        // COUPON FIELDS
        'coupon_id',
        'coupon_discount',
    ];

    protected $casts = [
        'items_total'         => 'decimal:2',
        'delivery_fee'        => 'decimal:2',
        'service_charge'      => 'decimal:2',
        'grand_total'         => 'decimal:2',
        'coupon_discount'     => 'decimal:2',
        'estimated_delivery_time' => 'datetime',
        'actual_delivery_time'    => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'GO-' . Str::random(8);
            }

            if (!$order->delivery_code) {
                $order->delivery_code = Str::random(6);
            }

            if (!$order->status) {
                $order->status = 'ordered';
            }

            if (!$order->payment_status) {
                $order->payment_status = 'pending';
            }
        });
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatcher_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function userBusStop()
    {
        return $this->belongsTo(PopularBusStop::class, 'user_bus_stop_id');
    }

    public function vendorBusStop()
    {
        return $this->belongsTo(PopularBusStop::class, 'vendor_bus_stop_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    public function getProgressStepsAttribute()
    {
        return [
            'ordered'   => 'Order Placed',
            'paid'      => 'Payment Confirmed',
            'accepted'  => 'Vendor Received Order',
            'packaged'  => 'Packaged & Ready',
            'picked_up' => 'Picked Up by Dispatcher',
            'enroute'   => 'Enroute to You',
            'delivered' => 'Delivered',
        ];
    }

    public function isCompleted()
    {
        return $this->status === 'delivered';
    }
}