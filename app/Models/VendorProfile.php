<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'type',                 // kitchen, supermarket, pharmacy
        'logo',
        'address',
        'bank_name',
        'account_number',
        'account_name',
        'is_verified',
        'popular_bus_stop_id',   // ← Added for delivery location
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function popularBusStop()
    {
        return $this->belongsTo(PopularBusStop::class, 'popular_bus_stop_id');
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, User::class, 'id', 'vendor_id', 'user_id', 'id');
    }
}