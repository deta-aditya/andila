<?php

namespace App\Validations;

use App\Validations\Validation;

class AgentValidation extends Validation
{
	/**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('agents'), [
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
	        'name' => 'required|max:255',
	        'email' => 'required|email|max:255',
	        'phone' => 'required|string',
	        'owner' => 'required|string',
	        'location' => 'required|array',
	        'location.*' => 'required|numeric',
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
            'subagents' => 'sometimes|boolean',
            'schedules' => 'sometimes|boolean',
            'subschedules' => 'sometimes|boolean',
            'stations' => 'sometimes|boolean',
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
            'ids.*' => 'required_with:ids|exists:andila.agents,id,active,0',
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
            'ids.*' => 'required_with:ids|exists:andila.agents,id,active,1',
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
	        'address.detail' => 'sometimes|string',
	        'address.subdistrict' => 'sometimes|string',
	        'address.district' => 'sometimes|string',
	        'address.regency' => 'sometimes|string',
	        'address.province' => 'sometimes|string',
	        'address.postal_code' => 'sometimes|numeric',
	    ];
    }
}
