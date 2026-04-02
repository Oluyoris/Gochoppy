<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_type',        // 'customer', 'main', 'delivery', 'service'
        'balance',
        'total_earned',
    ];

    protected $casts = [
        'balance'       => 'decimal:2',
        'total_earned'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    // Scopes
    public function scopeCustomer($query)
    {
        return $query->where('wallet_type', 'customer');
    }

    public function scopeDelivery($query)
    {
        return $query->where('wallet_type', 'delivery');
    }

    public function scopeService($query)
    {
        return $query->where('wallet_type', 'service');
    }
}