<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
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
    	'handleable_id', 
    	'handleable_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'handleable_id',
        'handleable_type',
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['handling'];

    /**
     * Get the handling name for user.
     *
     * @return string
     */
    public function getHandlingAttribute()
    {
        return value(function () {
            if (! array_has($this->attributes, 'handleable_type') || is_null($this->attributes['handleable_type'])) {
                return 'Administrator';
            } else {
                list($app, $models, $type) = explode('\\', $this->attributes['handleable_type']);
                return $type;
            }
        });
    }

    /**
     * Get the owning handleable model.
     * Handleable models are:
     * - Agent
     * - Subagent
     */
    public function handleable()
    {
        return $this->morphTo();
    }

    /**
     * Get the messages sent for the user (unsafe).
     */
    public function unsafeInbox()
    {
        return $this->hasMany('App\Models\Message', 'receiver_id');
    }

    /**
     * Get the messages sent for the user.
     */
    public function inbox()
    {
        return $this->unsafeInbox()->isSent();
    }

    /**
     * Get the messages sent by the user (unsafe).
     */
    public function unsafeOutbox()
    {
        return $this->hasMany('App\Models\Message', 'sender_id');
    }

    /**
     * Get the messages sent by the user.
     */
    public function outbox()
    {
        return $this->unsafeOutbox()->isSent();
    }

    /**
     * Get the draft messages composed by the user.
     */
    public function draftbox()
    {
        return $this->unsafeOutbox()->isDraft();
    }

    /**
     * Get the users which are having "conversation" via message with the user.
     */
    public function conversations()
    {
        return $this->belongsToMany('App\Models\User', 'messages', 'receiver_id', 'sender_id')->withTimestamps();
    }

    /**
     * Get the message attachments uploaded by the user.
     */
    public function attachments()
    {
        return $this->hasManyThrough('App\Models\Attachment', 'App\Models\Message', 'sender_id', 'message_id');
    }

    /**
     * Check whether the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return is_null($this->handleable);
    }

    /**
     * Check whether the user is handling an agent.
     *
     * @return bool
     */
    public function isAgent()
    {
        return $this->getHandlingAttribute() === 'Agent';
    }

    /**
     * Check whether the user is handling an subagent.
     *
     * @return bool
     */
    public function isSubagent()
    {
        return $this->getHandlingAttribute() === 'Subagent';
    }

    /**
     * Scope a query to only include or exclude administrators.
     *
     * @return Builder
     */
    public function scopeIsAdmin($query, $flag = true)
    {
        return $query->whereNull('handleable_type', 'and', $flag);
    }

    /**
     * Scope a query to only include users of a specified handling.
     *
     * @return Builder
     */
    public function scopeIsHandling($query, $handling)
    {
        return $query->isAdmin(false)->where('handleable_type', 'App\\Models\\'. $handling);
    }
}
