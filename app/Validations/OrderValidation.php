<?php

namespace App\Validations;

use App\Validations\Validation;
use App\Models\Schedule;
use Illuminate\Validation\Validator;

class OrderValidation extends Validation
{
	/**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('orders'), [
            'station' => 'sometimes|exists:andila.stations,id',
            'agent' => 'sometimes|exists:andila.agents,id',
            'accepted' => 'sometimes|boolean',
            'quantity' => 'sometimes',
            'range' => 'sometimes',
        ]);
    }

    /**
     * Get rules for single request.
     *
     * @return array
     */
    public function rulesSingle()
    {
        return [
            'schedule_id' => 'required|exists:andila.schedules,id',
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
            'subschedules' => 'sometimes|boolean',
        ];
    }

    /**
     * Get rules for accept request.
     *
     * @return array
     */
    public function rulesAccept()
    {
        return [
            'order_id' => 'required|exists:andila.orders,id,accepted_date,NULL',
        ];
    }

    /**
     * Get rules for accepts request.
     *
     * @return array
     */
    public function rulesAccepts()
    {
        return [
            'ids.*' => 'required|exists:andila.orders,id,accepted_date,NULL',
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
        $validator->after(function ($v) use ($additional) {
        	
        	if (! is_null($additional['range'])) {
            	$dates = explode('_', $additional['range']);

	            // If there are not exactly two dates in the range
	            // Error will be inserted
	            if (count($dates) !== 2) {
	                $this->addRangeError($v);
	            }

	            // Loops through the exploded date range
	            foreach ($dates as $date) {
	                $info = date_parse_from_format('Y-m-d', $date);

	                // If one of the date does not match the format
	                // Error will be inserted
	                if ($info['error_count'] > 0) {
	                    $this->addRangeError($v);
	                }
	            }
        	}

        	if(! is_null($additional['quantity'])) {
        		list($operator, $value) = explode(':', $additional['quantity']);

	            // If the operator is invalid, error will be inserted.
	            // This step is hardcoded for some reason.
	            if ($operator !== '=' && $operator !== '<' && $operator !== '>' && $operator !== '<=' && $operator !== '>=' && $operator !== '<>') {
	            	$this->addQuantityError($v);
	            }

	            // If the value is a negative integer
	            // Error will be inserted.
	            if (! is_numeric($value) || (int)$value < 0) {
	            	$this->addQuantityError($v);	
	            }
        	}
            
        });

        return $validator;
    }

    /**
     * Run extra validation of single request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterSingle(Validator $validator, $additional)
    {
    	if (is_null($additional['schedule_id'])) {
            return $validator;
        }

        $validator->after(function ($v) use ($additional) {

            // If the requested schedule already have an order in it, error will be inserted
            if (Schedule::ordered(false)->where('id', $additional['schedule_id'])->count() < 1) {
                $v->errors()->add('schedule_id', 'The requested schedule ID already have an order.');
            }

        });

        return $validator;
    }

    /**
     * Add "range" field error to validator.
     * I separated this due to its multiple calls.
     *
     * @param  Validator  $validator
     * @return void
     */
    protected function addRangeError(Validator $validator)
    {
        $validator->errors()->add('range', 'The range attribute should contain two different dates under "Y-m-d" format separated with underscore (_).');
    }

    /**
     * Add "quantity" field error to validator.
     * That's right! I separated this too due to its multiple calls.
     *
     * @param  Validator  $validator
     * @return void
     */
    protected function addQuantityError(Validator $validator)
    {
        $validator->errors()->add('quantity', 'The quantity should be a pair of operator and value separated with colon (operator:value), where operator is valid according to documentation and value is not a negative integer');
    }
}
