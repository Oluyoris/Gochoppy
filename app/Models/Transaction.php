<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transactable_id',
        'transactable_type',
        'user_id',
        'amount',
        'type',           // credit or debit
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function transactable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}