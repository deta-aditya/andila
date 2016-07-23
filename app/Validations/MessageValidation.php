<?php

namespace App\Validations;

use App\Validations\Validation;
use Illuminate\Validation\Validator;

class MessageValidation extends Validation
{
    /**
     * Get rules for show request.
     *
     * @return array
     */
    public function rulesShow()
    {
    	return [
    		'sender' => 'sometimes|boolean',
    		'receiver' => 'sometimes|boolean',
	    ];
    }

    /**
     * Get rules for inbox request.
     *
     * @return array
     */
    public function rulesInbox()
    {
    	return array_merge($this->basicIndexRules('messages'), [
    		'sender' => 'sometimes|exists:andila.users,id',
    		'search' => 'sometimes|string|max:255',
    		'priority' => 'sometimes|integer|between:0,3',
    		'read' => 'sometimes|boolean',
	    ]);
    }

    /**
     * Get rules for outbox request.
     *
     * @return array
     */
    public function rulesOutbox()
    {
    	return array_merge($this->basicIndexRules('messages'), [
    		'receiver' => 'sometimes|exists:andila.users,id',
    		'search' => 'sometimes|string|max:255',
    		'priority' => 'sometimes|integer|between:0,3',
	    ]);
    }

    /**
     * Get rules for draftbox request.
     *
     * @return array
     */
    public function rulesDraftbox()
    {
    	return array_merge($this->basicIndexRules('messages'), [
    		'priority' => 'sometimes|integer|between:0,3',
	    ]);
    }

    /**
     * Get rules for store single send request.
     *
     * @return array
     */
    public function rulesSingleSend()
    {
    	return [
	        'sender_id' => 'required|exists:andila.users,id',
	        'receiver_id' => 'required|exists:andila.users,id',
	        'subject' => 'sometimes|string|max:255',
	        'content' => 'required|string',
	        'priority' => 'required|integer|between:0,3',
	    ];
    }

    /**
     * Get rules for store single draft request.
     *
     * @return array
     */
    public function rulesSingleDraft()
    {
    	return [
	        'sender_id' => 'required|exists:andila.users,id',
	        'receiver_id' => 'sometimes|exists:andila.users,id',
	        'subject' => 'sometimes|string|max:255',
	        'content' => 'sometimes|string',
	        'priority' => 'sometimes|integer|between:0,3',
	    ];
    }

    /**
     * Get rules for send request.
     *
     * @return array
     */
    public function rulesSend()
    {
    	// Some of you may wonder why the rule is empty.
        // The thing is, we still need this method to return an array
        // in order to enter the "after validation" method,
        // which is the core validation part of send request.
    	return [
    		//
	    ];
    }

    /**
     * Get rules for read request.
     *
     * @return array
     */
    public function rulesRead()
    {
    	// Same as above.
    	// Do you think I will write those sentences all over again? Hah? No way!
    	return [
    		//
	    ];
    }

    /**
     * Get rules for Update request.
     *
     * @return array
     */
    public function rulesUpdate()
    {
    	return [
    		'receiver_id' => 'sometimes|exists:andila.users,id',
	        'subject' => 'sometimes|string|max:255',
	        'content' => 'sometimes|string',
	        'priority' => 'sometimes|integer|between:0,3',
	    ];
    }

    /**
     * Run extra validation of send request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterSend(Validator $validator, $additional)
    {
    	$message = $additional['message'];

    	$validator->sometimes('receiver_id', 'required|exists:andila.users,id', function ($input) use ($message) {
    		return $message->receiver_id === 0;
    	});

    	$validator->sometimes('subject', 'sometimes|string|max:255', function ($input) use ($message) {
    		return is_null($message->subject);
    	});

    	$validator->sometimes('content', 'required|string', function ($input) use ($message) {
    		return is_null($message->content);
    	});

    	$validator->after(function ($v) use ($message) {

    		// If the requested message is not "modifyable", error will be inserted.
            // Modifyable here means that the message hasn't yet been sent. See the App\Models\Message@isModifyable for more information.
    		if (! $message->isModifyable()) {
    			$v->errors()->add('message', 'The requested message is not modifyable. Please refer to the documentation for more information.');
    		}

    	});

    	return $validator;
    }

    /**
     * Run extra validation of read request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterRead(Validator $validator, $additional)
    {
    	$message = $additional['message'];

    	$validator->after(function ($v) use ($message) {

    		// If the requested message has been sent, error will be inserted.
    		// Using "isModifyable()" gives the same effect with checking whether the message has been sent.
    		if ($message->isModifyable()) {
    			$v->errors()->add('message', 'The requested message has been sent. Please refer to the documentation for more information.');
    		}

    	});

    	return $validator;
    }

    /**
     * Run extra validation of update request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterUpdate(Validator $validator, $additional)
    {
    	$message = $additional['message'];

    	$validator->after(function ($v) use ($message) {

    		// If the requested message is not "modifyable", error will be inserted.
            // Modifyable here means that the message hasn't yet been sent. See the App\Models\Message@isModifyable for more information.
    		if (! $message->isModifyable()) {
    			$v->errors()->add('message', 'The requested message is not modifyable. Please refer to the documentation for more information.');
    		}

    	});

    	return $validator;
    }
}
