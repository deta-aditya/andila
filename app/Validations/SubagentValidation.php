<?php

namespace App\Validations;

use App\Repositories\SubagentRepository;
use App\Validations\Validation;
use Illuminate\Validation\Validator;

class SubagentValidation extends Validation
{
	/**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('subagents'), [
            'agent' => 'sometimes|exists:andila.agents,id',
            'contract_value' => 'sometimes',
            'active' => 'sometimes|boolean',
            'schedulable' => 'sometimes|boolean',
        ]);
    }

    /**
     * Get rules for store single request.
     *
     * @return array
     */
    public function rulesSingle()
    {
    	return [
            'agent_id' => 'required|exists:andila.agents,id',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string',
            'owner' => 'required|string',
            'location' => 'required|array',
            'location.*' => 'required|numeric',
            'contract_value' => 'required|numeric|between:'. SubagentRepository::CONTRACT_VALUE_MIN .','. SubagentRepository::CONTRACT_VALUE_MAX,
            'address.detail' => 'sometimes|string',
	        'address.subdistrict' => 'sometimes|string',
	        'address.district' => 'sometimes|string',
	        'address.regency' => 'sometimes|string',
	        'address.province' => 'sometimes|string',
	        'address.postal_code' => 'sometimes|numeric',
	        'user.email' => 'sometimes|required_with:user.password|email|max:255|unique:andila.users,email',
	        'user.password' => 'sometimes|required_with:user.email|string|min:5',
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
            'agent' => 'sometimes|boolean',
            'subschedules' => 'sometimes|boolean',
        ];
    }

    /**
     * Get rules for activates request.
     *
     * @return array
     */
    public function rulesActivates()
    {
        return [
        	'ids' => 'required|array',
            'ids.*' => 'required_with:ids|exists:andila.subagents,id,active,0',
        ];
    }

    /**
     * Get rules for deactivates request.
     *
     * @return array
     */
    public function rulesDeactivates()
    {
        return [
        	'ids' => 'required|array',
            'ids.*' => 'required_with:ids|exists:andila.subagents,id,active,1',
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
            'name' => 'sometimes|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string',
            'owner' => 'sometimes|string',
            'location' => 'sometimes|array',
            'location.*' => 'required_with:location|numeric',
            'contract_value' => 'sometimes|numeric|between:'. SubagentRepository::CONTRACT_VALUE_MIN .','. SubagentRepository::CONTRACT_VALUE_MAX,
            'address.detail' => 'sometimes|string',
	        'address.subdistrict' => 'sometimes|string',
	        'address.district' => 'sometimes|string',
	        'address.regency' => 'sometimes|string',
	        'address.province' => 'sometimes|string',
	        'address.postal_code' => 'sometimes|numeric',
        ];
    }

    /**
     * Run extra validation of index request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterIndex(Validator $validator, $additional)
    {
        if (is_null($additional['contract_value'])) {
            return $validator;
        }

        $validator->after(function ($v) use ($additional) {
            list($operator, $value) = explode(':', $additional['contract_value']);

            // If the operator is invalid, error will be inserted.
            // This step is hardcoded for some reason.
            if ($operator !== '=' && $operator !== '<' && $operator !== '>' && $operator !== '<=' && $operator !== '>=' && $operator !== '<>') {
            	$this->addContractValueError($v);
            }

            // If the value is a negative integer
            // Error will be inserted.
            if (! is_numeric($value) || (int)$value < 0) {
            	$this->addContractValueError($v);	
            }

        });

        return $validator;
    }

    /**
     * Add "contract_value" field error to validator.
     * That's right! I separated this due to its multiple calls.
     *
     * @param  Validator  $validator
     * @return void
     */
    protected function addContractValueError(Validator $validator)
    {
        $validator->errors()->add('contract_value', 'The contract_value should be a pair of operator and value separated with colon (operator:value), where operator is valid according to documentation and value is not a negative integer');
    }
}
