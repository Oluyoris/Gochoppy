<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_amount',
        'applicable_categories',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'applicable_categories' => 'array',
        'discount_amount'       => 'decimal:2',
        'expires_at'            => 'datetime',
        'is_active'             => 'boolean',
    ];

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->used_count >= $this->max_uses) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;

        return true;
    }

    public function canApplyTo(string $category): bool
    {
        return in_array($category, $this->applicable_categories ?? []);
    }

    /**
     * Users who have used this coupon
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')
                    ->withPivot('used_at')
                    ->withTimestamps();
    }
}