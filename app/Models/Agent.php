<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
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
    	'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['contract_value', 'schedulable'];

    /**
     * Get the agent's contract values.
     *
     * @param  string  $value
     * @return array
     */
    public function getContractValueAttribute()
    {
        if ($this->has('subagents')) {
            return $this->contractValues();
        } else {
            return 0;
        }
    }

    /**
     * Get the agent's schedulable attribute.
     *
     * @param  string  $value
     * @return array
     */
    public function getSchedulableAttribute()
    {
        return $this->attributes['active'] && $this->has('subagents');
    }

    /**
     * Get the agent's location.
     *
     * @param  string  $value
     * @return array
     */
    public function getLocationAttribute($value)
    {
        return explode(',', $value);
    }

    /**
     * Set the agent's location.
     *
     * @param  array  $value
     * @return string
     */
    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = implode(',', $value);
    }

    /**
     * Get the user model associated with the agent.
     */
    public function user()
    {
        return $this->morphOne('App\Models\User', 'handleable');
    }

    /**
     * Get the address model associated with the agent.
     */
    public function address()
    {
        return $this->morphOne('App\Models\Address', 'addressable');
    }

    /**
     * Get the subagents supervised by the agent.
     */
    public function subagents()
    {
        return $this->hasMany('App\Models\Subagent');
    }

    /**
     * Get the schedules of which the agent is responsible to.
     */
    public function schedules()
    {
        return $this->hasMany('App\Models\Schedule');
    }

    /**
     * Get the subschedules related to the agent.
     */
    public function subschedules()
    {
        return $this->hasManyThrough('App\Models\Subschedule', 'App\Models\Subagent');
    }

    /**
     * Get the stations which are distributing the agent.
     */
    public function stations()
    {
        return $this->belongsToMany('App\Models\Station', 'schedules');
    }

    /**
     * Get the quantity of the subagent's contract values under the agent's supervision.
     *
     * @return int
     */
    public function contractValues()
    {
        return $this->subagents()->sum('contract_value');
    }

    /**
     * Scope a query to only include or exclude active agents.
     *
     * @return Builder
     */
    public function scopeIsActive($query, $active = true)
    {
        return $query->where('active', (int)$active);
    }

    /**
     * Scope a query to only include agents which have at least one subagent.
     *
     * @return Builder
     */
    public function scopeHasSubagents($query)
    {
        return $query->has('subagents');
    }

    /**
     * Scope a query to only include agents that are allowed to have schedule.
     *
     * @return Builder
     */
    public function scopeSchedulable($query)
    {
        return $query->isActive()->hasSubagents();
    }
}
