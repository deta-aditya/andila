<?php

namespace App\Repositories;

use App\Models\Message;
use App\Models\User;
use App\Validations\MessageValidation;
use App\Repositories\Repository;
use Carbon\Carbon;

class MessageRepository extends Repository
{
    /**
     * The message validation instance.
     *
     * @var MessageValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  MessageValidation  $validation
     * @return void
     */
    public function __construct(MessageValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the inbox messages for specified user with specified queries.
     *
     * @param  User  $user
     * @param  array  $params
     * @return array
     */
    public function inbox(User $user, $params)
    {
        $query = $this->select($user->inbox()->getQuery(), $params, false);

        if ( array_has($params, 'sender') ) {
            $query->isSentBy($params['sender']);
        }

        if ( array_has($params, 'search') ) {
            $query->search($params['search']);
        }

        if ( array_has($params, 'priority') ) {
            $query->hasPriority($params['priority']);
        }

        if ( array_has($params, 'read') ) {
            $query->isRead($params['read']);
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Get all of the outbox messages for specified user with specified queries.
     *
     * @param  User  $user
     * @param  array  $params
     * @return array
     */
    public function outbox(User $user, $params)
    {
        $query = $this->select($user->outbox()->getQuery(), $params, false);

        if ( array_has($params, 'receiver') ) {
            $query->isSentTo($params['receiver']);
        }

        if ( array_has($params, 'search') ) {
            $query->search($params['search']);
        }

        if ( array_has($params, 'priority') ) {
            $query->hasPriority($params['priority']);
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Get all of the draftbox messages for specified user with specified queries.
     *
     * @param  User  $user
     * @param  array  $params
     * @return array
     */
    public function draftbox(User $user, $params)
    {
        $query = $this->select($user->draftbox()->getQuery(), $params, false);

        if ( array_has($params, 'priority') ) {
            $query->hasPriority($params['priority']);
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Get the specified message model.
     *
     * @param  Message  $message
     * @param  array  $params
     * @return array
     */
    public function show(Message $message, $params)
    {
        $message->load('attachments');

        if ( array_has($params, 'sender') ) {
            ! (bool)$params['sender'] ?: $message->load('sender'); 
        }

        if ( array_has($params, 'receiver') ) {
            ! (bool)$params['receiver'] ?: $message->load('receiver'); 
        }

        return $this->extractResource($message, 'messages');
    }

    /**
     * Store a new message model.
     *
     * @param  array  $data
     * @param  bool  $send
     * @return array
     */
    public function single($data, $send)
    {
        $messageData = array_only($data, ['sender_id', 'receiver_id', 'subject', 'content', 'priority']);

        $message = new Message($messageData);
        
        $send === true
            ? $message->sent_at = Carbon::now()->toDateTimeString()
            : $message->draft = true;

        $message->save();

        return $this->extractResource($message, 'messages');
    }

    /**
     * Send a message model.
     *
     * @param  Message  $message
     * @param  array  $params
     * @return array
     */
    public function send(Message $message, $params)
    {
        $messageData = array_only($params, ['sender_id', 'receiver_id', 'subject', 'content', 'priority']);

        $message->update($messageData);
        $message->sent_at = Carbon::now()->toDateTimeString();
        $message->draft = false;
        $message->save();

        return $this->extractResource($message, 'messages');
    }

    /**
     * Read a message model.
     *
     * @param  Message  $message
     * @return array
     */
    public function read(Message $message)
    {
        $message->read_at = Carbon::now()->toDateTimeString();
        $message->save();

        return $this->extractResource($message, 'messages');
    }

    /**
     * Update the specified message model.
     *
     * @param  Message  $message
     * @param  array  $params
     * @return array
     */
    public function update(Message $message, $params)
    {
        $messageData = array_only($params, ['receiver_id', 'subject', 'content', 'priority']);

        $message->update($messageData);

        return $this->extractResource($message, 'messages');
    }
}
