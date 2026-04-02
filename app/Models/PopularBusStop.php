<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularBusStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Intervals where this stop is the starting point (FROM)
     */
    public function fromIntervals()
    {
        return $this->hasMany(DeliveryInterval::class, 'from_stop_id');
    }

    /**
     * Intervals where this stop is the destination point (TO)
     */
    public function toIntervals()
    {
        return $this->hasMany(DeliveryInterval::class, 'to_stop_id');
    }

    /**
     * Get price and time from this stop to another stop
     */
    public function getIntervalTo(PopularBusStop $toStop): ?DeliveryInterval
    {
        return $this->fromIntervals()
                    ->where('to_stop_id', $toStop->id)
                    ->first();
    }
}