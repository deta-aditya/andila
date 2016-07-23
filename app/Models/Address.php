<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
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
    	'addressable_id',
    	'addressable_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'addressable_id', 
        'addressable_type',
    ];

    /**
     * Get the owning addressable model.
     * Addressable models are:
     * - Station
     * - Agent
     * - Stand
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include addresses of a specified province.
     *
     * @return Builder
     */
    public function scopeOfProvince($query, $province)
    {
        return $query->where('province', strtoupper($province));
    }

    /**
     * Scope a query to only include addresses of a specified regency.
     *
     * @return Builder
     */
    public function scopeOfRegency($query, $regency)
    {
        return $query->where('regency', strtoupper($regency));
    }

    /**
     * Scope a query to only include addresses of a specified district.
     *
     * @return Builder
     */
    public function scopeOfDistrict($query, $district)
    {
        return $query->where('district', strtoupper($district));
    }

    /**
     * Scope a query to only include addresses of a specified subdistrict.
     *
     * @return Builder
     */
    public function scopeOfSubdistrict($query, $subdistrict)
    {
        return $query->where('subdistrict', strtoupper($subdistrict));
    }
}
