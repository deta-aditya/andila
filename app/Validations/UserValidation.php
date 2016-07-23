<?php

namespace App\Validations;

use Auth;
use App\Validations\Validation;
use Illuminate\Validation\Validator;

class UserValidation extends Validation
{
    /**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return $this->basicIndexRules('users');
    }

    /**
     * Get rules for store administrator request.
     *
     * @return array
     */
    public function rulesAdmin()
    {
        return [
            'email' => 'required|email|max:255|unique:andila.users,email',
            'password' => 'required|string|min:5',
        ];
    }

    /**
     * Get rules for authentication attempt request.
     *
     * @return array
     */
    public function rulesAuth()
    {
        return [
            'email' => 'required|email|exists:andila.users,email',
            'password' => 'required|string',
        ];
    }

    /**
     * Get rules for show request.
     *
     * @return array
     */
    public function rulesShow()
    {
        return [
            'handleable' => 'sometimes|boolean',
            'messages' => 'sometimes|boolean',
            'conversations' => 'sometimes|boolean',
            'attachments' => 'sometimes|boolean',
        ];
    }

	/**
     * Get rules for update request.
     *
     * @return array
     */
    public function rulesUpdate()
    {
        return [
            'email' => 'sometimes|email|max:255|unique:andila.users,email',
            'password' => 'sometimes|string|min:5|confirmed', // This requires "password_confirmation" field to exist
            'password_old' => 'required_with:password,password_confirmation',
        ];
    }

    /**
     * Run extra validation of auth request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterAuth(Validator $validator, $additional)
    {
        if (is_null($additional['email']) || is_null($additional['password'])) {
            return $validator;
        }

        $validator->after(function ($v) use ($additional) {

            // If the credentials does not match
            // Error will be inserted.
            if (! Auth::once($additional)) {
                $v->errors()->add('credentials', 'The requested credentials do not match.');
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
        if (is_null($additional['email']) || is_null($additional['password'])) {
            return $validator;
        } 

        $validator->after(function ($v) use ($additional) {

            // If the pseudo-credentials does not match, error will be inserted
            // Actually it only checks the password but the hashing system
            // also requires the e-mail to present. So...
            if (! Auth::once($additional)) {
                $v->errors()->add('password_old', 'The requested old password does not match.');
            }
        });

        return $validator;
    }
}
