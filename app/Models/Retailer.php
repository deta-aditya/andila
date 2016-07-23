<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
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
     * Get the reports model related to the retailer.
     */
    public function reports()
    {
        return $this->belongsToMany('App\Models\Report')->withPivot('sales_qty');
    }
}
