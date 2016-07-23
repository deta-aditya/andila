<?php

namespace App\Models\Indonesia;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
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
     * Get the regencies located in the province.
     */
    public function regencies()
    {
        return $this->hasMany('App\Models\Indonesia\Regency');
    }
}
