<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
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
        'draft',
        'sent_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'draft',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'draft' => 'boolean',
    ];

    /**
     * Get the user who sends the message.
     */
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    /**
     * Get the user who receives the message.
     */
    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }

    /**
     * Get the attachments of the message.
     */
    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
    }

    /**
     * Check whether the current message is modifyable.
     */
    public function isModifyable()
    {
        return is_null($this->sent_at);
    }

    /**
     * Scope a query to only include messages that match the specified search query.
     *
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) {
            $q->where('subject', 'LIKE', '%'. $search .'%')
              ->orWhere('content', 'LIKE', '%'. $search .'%');
        });
    }

    /**
     * Scope a query to only include messages that are sent by specified user, identified by its ID.
     *
     * @return Builder
     */
    public function scopeIsSentBy($query, $id)
    {
        return $query->where('sender_id', $id);
    }

    /**
     * Scope a query to only include messages that are sent to specified user, identified by its ID.
     *
     * @return Builder
     */
    public function scopeIsSentTo($query, $id)
    {
        return $query->where('receiver_id', $id);
    }

    /**
     * Scope a query to only include or exclude draft messages.
     *
     * @return Builder
     */
    public function scopeIsDraft($query, $flag = true)
    {
        return $query->where('draft', (int)$flag);
    }

    /**
     * Scope a query to only include or exclude read messages.
     *
     * @return Builder
     */
    public function scopeIsRead($query, $flag = true)
    {
        return $query->whereNull('read_at', 'and', $flag);
    }

    /**
     * Scope a query to only include or exclude sent messages.
     *
     * @return Builder
     */
    public function scopeIsSent($query, $flag = true)
    {
        return $query->whereNull('sent_at', 'and', $flag);
    }

    /**
     * Scope a query to only include messages with specified range of sent date.
     *
     * @return Builder
     */
    public function scopeSentBetween($query, $from, $to)
    {
    	return $query->isSent()->whereBetween('sent_at', [$from, $to]);
    }

    /**
     * Scope a query to only include or exclude messages with specified priority.
     *
     * @return Builder
     */
    public function scopeHasPriority($query, $operatorOrValue, $value = null)
    {
        return $query->where('priority', $operatorOrValue, $value);
    }
}
