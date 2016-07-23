<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
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
     * Get the message where the attachment is attached to.
     */
    public function message()
    {
        return $this->belongsTo('App\Models\Message');
    }

    /**
     * Scope a query to only include attachments that are attached to specific message, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfMessage($query, $id)
    {
        return $query->where('message_id', $id);
    }

    /**
     * Scope a query to only include attachments that are uploaded by specific user, identified by its ID.
     *
     * @return Builder
     */
    public function scopeOfUser($query, $id)
    {
        return $query->whereHas('message', function ($q) use ($id) {
            $q->isSentBy($id);
        });
    }
}
