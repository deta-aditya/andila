<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
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
        'reported_at',
    ];

    /**
     * Get the subschedule model related to the report.
     */
    public function subschedule()
    {
        return $this->belongsTo('App\Models\Subschedule');
    }

    /**
     * Get the retailers model related to the report.
     */
    public function retailers()
    {
        return $this->belongsToMany('App\Models\Retailer')->withPivot('sales_qty');
    }

    /**
     * Scope a query to only include reports that are related to specified subschedule, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfSubschedule($query, $id)
    {
        return $query->where('subschedule_id', $id);
    }

    /**
     * Scope a query to only include reports that are related to specified station, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfStation($query, $id)
    {
        return $query->whereHas('subschedule', function ($q) use ($id) {
            $q->ofStation($id);
        });
    }

    /**
     * Scope a query to only include reports that are related to specified agent, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfAgent($query, $id)
    {
        return $query->whereHas('subschedule', function ($q) use ($id) {
            $q->ofAgent($id);
        });
    }

    /**
     * Scope a query to only include reports that are related to specified subagent, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfSubagent($query, $id)
    {
        return $query->whereHas('subschedule', function ($q) use ($id) {
            $q->ofSubagent($id);
        });        
    }

    /**
     * Scope a query to only include reports that are related to specified schedule, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfSchedule($query, $id)
    {
        return $query->whereHas('subschedule', function ($q) use ($id) {
            $q->ofSchedule($id);
        });        
    }

    /**
     * Scope a query to only include reports that are related to specified order, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfOrder($query, $id)
    {
        return $query->whereHas('subschedule', function ($q) use ($id) {
            $q->ofOrder($id);
        });        
    }

    /**
     * Scope a query to only include reports of a specified range of scheduled dates.
     *
     * @return Builder
     */
    public function scopeScheduledBetween($query, $range)
    {
        return $query->whereHas('subschedule', function ($q) use ($range) {
            $q->scheduledBetween($range);
        });
    }

    /**
     * Scope a query to only include reports with specified allocation quantity.
     *
     * @return Builder
     */
    public function scopeHasAllocation($query, $operatorOrQuota, $quota = null)
    {
        return $query->where('allocated_qty', $operatorOrQuota, $quota);
    }

    /**
     * Scope a query to only include reports which have already been reported.
     *
     * @return Builder
     */
    public function scopeReported($query, $flag = true)
    {
        return $flag === true 
            ? $query->where('reported_at', '<>', null)
            : $query->where('reported_at', '=', null);
    }
}
