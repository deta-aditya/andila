<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
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
    	'id'
    ];

    /**
     * Get the station's location.
     *
     * @param  string  $value
     * @return array
     */
    public function getLocationAttribute($value)
    {
        return explode(',', $value);
    }

    /**
     * Set the station's location.
     *
     * @param  array  $value
     * @return string
     */
    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = implode(',', $value);
    }

    /**
     * Get the address model associated with the station.
     */
    public function address()
    {
        return $this->morphOne('App\Models\Address', 'addressable');
    }

    /**
     * Get the schedules of which the station is responsible to.
     */
    public function schedules()
    {
        return $this->hasMany('App\Models\Schedule');
    }

    /**
     * Get the agents of which distributions from the station are allocated to.
     */
    public function agents()
    {
        return $this->belongsToMany('App\Models\Agent', 'schedules');
    }

    /**
     * Scope a query to only include or exclude stations of a specified type.
     *
     * @return Builder
     */
    public function scopeOfType($query, $type, $include = true)
    {
        return $include === true
        	? $query->where('type', $type)
        	: $query->where('type', '<>', $type);
    }
}
