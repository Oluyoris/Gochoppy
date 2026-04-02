<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryInterval extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_stop_id',
        'to_stop_id',
        'price',
        'estimated_time',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * The stop this interval starts from
     */
    public function fromStop()
    {
        return $this->belongsTo(PopularBusStop::class, 'from_stop_id');
    }

    /**
     * The stop this interval goes to
     */
    public function toStop()
    {
        return $this->belongsTo(PopularBusStop::class, 'to_stop_id');
    }
}