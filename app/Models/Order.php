<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'andila';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    	'id',
        'quantity',
        'accepted_date',
    ];

    /**
     * Get the schedule of which the order is sent for.
     */
    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule');
    }

    /**
     * Get the subschedule for the order.
     */
    public function subschedules()
    {
        return $this->hasMany('App\Models\Subschedule');
    }

    /**
     * Scope a query to only include order that are related to specified schedule, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfSchedule($query, $id)
    {
        return $query->where('schedule_id', $id);
    }

    /**
     * Scope a query to only include orders that are sent for specified station, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfStation($query, $id)
    {
        return $query->whereHas('schedule', function ($q) use ($id) {
            $q->ofStation($id);
        });
    }

    /**
     * Scope a query to only include orders that are made by specified agent, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfAgent($query, $id)
    {
        return $query->whereHas('schedule', function ($q) use ($id) {
            $q->ofAgent($id);
        });
    }

    /**
     * Scope a query to only include orders ordered for a specified range of scheduled dates.
     *
     * @return Builder
     */
    public function scopeScheduledBetween($query, $range)
    {
        return $query->whereHas('schedule', function ($q) use ($range) {
            $q->scheduledBetween($range);
        });
    }

    /**
     * Scope a query to only include orders with specified quantity.
     *
     * @return Builder
     */
    public function scopeHasQuantity($query, $operatorOrQuota, $quota = null)
    {
        return $query->where('quantity', $operatorOrQuota, $quota);
    }

    /**
     * Scope a query to only include accepted/not-yet-accepted orders.
     *
     * @return Builder
     */
    public function scopeAccepted($query, $flag = true)
    {
        return $flag === true 
            ? $query->where('accepted_date', '<>', null) 
            : $query->where('accepted_date', '=', null);
    }
}
