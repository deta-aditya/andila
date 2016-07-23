<?php

namespace App\Validations;

use App\Validations\Validation;
use Illuminate\Validation\Validator;

class AttachmentValidation extends Validation
{
	/**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('attachments'), [
            'message' => 'sometimes|exists:andila.messages,id',
            'user' => 'sometimes|exists:andila.users,id',
        ]);
    }

	/**
     * Get rules for multiple request.
     *
     * @return array
     */
    public function rulesMultiple()
    {
        return array_merge($this->basicIndexRules('attachments'), [
            'message' => 'sometimes|exists:andila.messages,id',
            'user' => 'sometimes|exists:andila.users,id',
        ]);
    }
}
