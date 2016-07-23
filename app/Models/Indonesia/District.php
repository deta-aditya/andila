<?php

namespace App\Models\Indonesia;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'indonesia';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    	'id'
    ];

    /**
     * Get the subdistricts located in the province.
     */
    public function subdistricts()
    {
        return $this->hasMany('App\Models\Indonesia\Subdistrict');
    }

    /**
     * Scope a query to only include districts that are located in specific regency, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfRegency($query, $id)
    {
        return $query->where('regency_id', $id);
    }
}
