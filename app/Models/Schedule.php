<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
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
    ];

    /**
     * Get the agent model related to the schedule.
     */
    public function agent()
    {
        return $this->belongsTo('App\Models\Agent');
    }

    /**
     * Get the station model related to the schedule.
     */
    public function station()
    {
        return $this->belongsTo('App\Models\Station');
    }

    /**
     * Get the order for the schedule.
     */
    public function order()
    {
        return $this->hasOne('App\Models\Order');
    }

    /**
     * Get the subschedules for the schedule.
     */
    public function subschedules()
    {
        return $this->hasManyThrough('App\Models\Subschedule', 'App\Models\Order');
    }

    /**
     * Check whether the current schedule is modifyable.
     */
    public function isModifyable()
    {
        return is_null($this->order);
    }

    /**
     * Return days interval until next month of the scheduled date
     */
    public function daysIntervalUntilNextMonth()
    {
        $scheduled_date = Carbon::parse($this->attributes['scheduled_date']);

        return $scheduled_date->diffInDays($scheduled_date->copy()->addMonth());
    }

    /**
     * Scope a query to only include schedules that are made for specified station, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfStation($query, $id)
    {
        return $query->where('station_id', $id);
    }

    /**
     * Scope a query to only include schedules that are made for specified agent, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfAgent($query, $id)
    {
        return $query->where('agent_id', $id);
    }

    /**
     * Scope a query to only include this month's schedule.
     *
     * @return Builder
     */
    public function scopeOfThisMonth($query)
    {
        return $query->whereRaw('DATE_FORMAT(`scheduled_date`, "%Y-%m") = DATE_FORMAT(NOW(), "%Y-%m")');
    }

    /**
     * Scope a query to only include schedules scheduled on a specified range of dates.
     *
     * @return Builder
     */
    public function scopeScheduledBetween($query, $range)
    {
        return $query->whereBetween('scheduled_date', $range);
    }

    /**
     * Scope a query to only include schedules which already have an order in it.
     *
     * @return Builder
     */
    public function scopeOrdered($query, $flag = true)
    {
        return $flag === true 
            ? $query->has('order')
            : $query->doesntHave('order');
    }
}
