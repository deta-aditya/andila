<?php

namespace App\Models\Indonesia;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
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
     * Scope a query to only include subdistricts that are located in specific district, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfDistrict($query, $id)
    {
        return $query->where('district_id', $id);
    }
}
