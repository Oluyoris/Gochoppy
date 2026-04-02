<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDeliverySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_percentage',
        'admin_percentage',
        'is_active',
    ];

    protected $casts = [
        'dispatch_percentage' => 'integer',
        'admin_percentage'    => 'integer',
        'is_active'           => 'boolean',
    ];

    /**
     * Get the active settings (there should be only one active row)
     */
    public static function getActive()
    {
        return self::where('is_active', true)->firstOrFail();
    }

    /**
     * Get dispatch share of delivery fee
     */
    public static function getDispatchShare(int $deliveryFee): int
    {
        $settings = self::getActive();
        return (int) round($deliveryFee * $settings->dispatch_percentage / 100);
    }

    /**
     * Get admin share of delivery fee
     */
    public static function getAdminShare(int $deliveryFee): int
    {
        $settings = self::getActive();
        return (int) round($deliveryFee * $settings->admin_percentage / 100);
    }
}