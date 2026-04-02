<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    // All known/allowed setting keys (for documentation + future validation)
    public const KEYS = [
        'service_charge_amount',
        'delivery_fee',
        'referral_bonus',
        'discount_amount',
        'paystack_enabled',
        'paystack_public_key',
        'paystack_secret_key',
        'manual_bank_enabled',
        'manual_bank_name',
        'manual_account_number',
        'manual_account_name',
        // Add new ones here when you need them (no migration needed)
    ];

    // Optional: helper to get typed value
    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'decimal', 'integer' => (float) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }
}