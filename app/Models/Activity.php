<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
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
     * Get the user model related to the activity.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
