<?php

namespace App\Models;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Model;

class Subagent extends Model
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
     * Get the subagent's location.
     *
     * @param  string  $value
     * @return array
     */
    public function getLocationAttribute($value)
    {
        return explode(',', $value);
    }

    /**
     * Set the subagent's location.
     *
     * @param  array  $value
     * @return string
     */
    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = implode(',', $value);
    }

    /**
     * Get the user model associated with the subagent.
     */
    public function user()
    {
        return $this->morphOne('App\Models\User', 'handleable');
    }

    /**
     * Get the address model associated with the subagent.
     */
    public function address()
    {
        return $this->morphOne('App\Models\Address', 'addressable');
    }

    /**
     * Get the agent supervising the subagent.
     */
    public function agent()
    {
        return $this->belongsTo('App\Models\Agent');
    }

    /**
     * Get the subschedules of which the subagent is responsible to.
     */
    public function subschedules()
    {
        return $this->hasMany('App\Models\Subschedule');
    }

    /**
     * Scope a query to only include or exclude active subagents.
     *
     * @return Builder
     */
    public function scopeIsActive($query, $active = true)
    {
        return $query->where('active', (int)$active);
    }

    /**
     * Scope a query to only include subagents with specified contract value.
     *
     * @return Builder
     */
    public function scopeHasContractValue($query, $operatorOrQuota, $quota = null)
    {
    	return $query->where('contract_value', $operatorOrQuota, $quota);
    }

    /**
     * Scope a query to only include subagents that are supervised by specific agent, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfAgent($query, $id)
    {
        return $query->where('agent_id', $id);
    }

    /**
     * Scope a query to only include subagents that are allowed to have subschedule.
     *
     * @return Builder
     */
    public function scopeSchedulable($query)
    {
        return $query->isActive();
    }
}
