<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'status',
        'reference',
        'proof',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship with the customer who made the deposit
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the admin who approved the deposit
     */
    public function approvedBy()
    {
        // Most common in Laravel admin setups:
        return $this->belongsTo(\App\Models\AdminUser::class, 'approved_by');
        
        // If your admin model is actually named "Admin", use this instead:
        // return $this->belongsTo(\App\Models\Admin::class, 'approved_by');
    }
}