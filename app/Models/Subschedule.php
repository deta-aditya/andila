<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subschedule extends Model
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
     * Get the order model related to the subschedule.
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * Get the report model dedicated to the subschedule.
     */
    public function report()
    {
        return $this->hasOne('App\Models\Report');
    }

    /**
     * Get the subagent model related to the subschedule.
     */
    public function subagent()
    {
        return $this->belongsTo('App\Models\Subagent');
    }

    /**
     * Scope a query to only include subschedules that are related to specified order, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfOrder($query, $id)
    {
        return $query->where('order_id', $id);
    }

    /**
     * Scope a query to only include subschedules that are related to specified schedule, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfSchedule($query, $id)
    {
        return $query->whereHas('order', function ($q) use ($id) {
            $q->ofSchedule($id);
        });
    }

    /**
     * Scope a query to only include subschedules that are related to specified station, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfStation($query, $id)
    {
        return $query->whereHas('order', function ($q) use ($id) {
            $q->ofStation($id);
        });
    }

    /**
     * Scope a query to only include subschedules that are related to specified agent, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfAgent($query, $id)
    {
        return $query->whereHas('order', function ($q) use ($id) {
            $q->ofAgent($id);
        });
    }

    /**
     * Scope a query to only include subschedules that are related to specified subagent, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfSubagent($query, $id)
    {
        return $query->where('subagent_id', $id);
    }

    /**
     * Scope a query to only include this month's subschedules.
     *
     * @return Builder
     */
    public function scopeOfThisMonth($query)
    {
        return $query->whereRaw('DATE_FORMAT(`scheduled_date`, "%Y-%m") = DATE_FORMAT(NOW(), "%Y-%m")');
    }

    /**
     * Scope a query to only include this week's subschedules.
     *
     * @return Builder
     */
    public function scopeOfThisWeek($query)
    {
        return $query->whereRaw('WEEKOFYEAR(`scheduled_date`) = WEEKOFYEAR(NOW())');
    }

    /**
     * Scope a query to only include subschedules scheduled on a specified range of dates.
     *
     * @return Builder
     */
    public function scopeScheduledBetween($query, $range)
    {
        return $query->whereBetween('scheduled_date', $range);
    }

    /**
     * Scope a query to only include subschedules which have already been allocated to the subagent.
     *
     * @return Builder
     */
    public function scopeAllocated($query, $flag = true)
    {
        return $flag === true 
            ? $query->has('report')
            : $query->doesntHave('report');
    }
}
