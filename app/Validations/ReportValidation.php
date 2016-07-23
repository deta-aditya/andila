<?php

namespace App\Validations;

use App\Validations\Validation;
use App\Models\Subschedule;
use Illuminate\Validation\Validator;

class ReportValidation extends Validation
{
	/**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('reports'), [
            'station' => 'sometimes|exists:andila.stations,id',
            'agent' => 'sometimes|exists:andila.agents,id',
            'subagent' => 'sometimes|exists:andila.subagents,id',
            'schedule' => 'sometimes|exists:andila.schedules,id',
            'order' => 'sometimes|exists:andila.orders,id',
            'reported' => 'sometimes|boolean',
            'allocation' => 'sometimes',
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
        	'subschedule_id' => 'required|exists:andila.subschedules,id',
        	'allocated_qty' => 'required|integer',
        ];
    }

    /**
     * Get rules for complete request.
     *
     * @return array
     */
    public function rulesComplete()
    {
        return [
            'sales_retailers' => 'required|array',
            'sales_retailers.*.retailer_name' => 'required|string',
            'sales_retailers.*.sales_qty' => 'required|integer',
            'sales_household_qty' => 'required|integer',
            'sales_microbusiness_qty' => 'required|integer',
            'stock_empty_qty' => 'required|integer',
            'stock_filled_qty' => 'required|integer',
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

        	if(! is_null($additional['allocation'])) {
        		list($operator, $value) = explode(':', $additional['allocation']);

	            // If the operator is invalid, error will be inserted.
	            // This step is hardcoded for some reason.
	            if ($operator !== '=' && $operator !== '<' && $operator !== '>' && $operator !== '<=' && $operator !== '>=' && $operator !== '<>') {
	            	$this->addAllocationError($v);
	            }

	            // If the value is a negative integer
	            // Error will be inserted.
	            if (! is_numeric($value) || (int)$value < 0) {
	            	$this->addAllocationError($v);	
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
        if (is_null($additional['subschedule_id']) || is_null($additional['allocated_qty'])) {
            return $validator;
        }

        $validator->after(function ($v) use ($additional) {

            // If the requested subschedule already have an report in it, error will be inserted
            if (Subschedule::allocated(false)->where('id', $additional['subschedule_id'])->count() < 1) {
                $v->errors()->add('subschedule_id', 'The requested subschedule ID already have a report.');
            }

            // Alright.. This one is probably the most complicated part in Andila
            // Here we getting these 4 attributes: already, requested, contractValue, and daysInterval
            // Just like what the error said. Refer to documentation for more information.
            $subschedule = Subschedule::find($additional['subschedule_id']);
            $alreadyAllocated = ! is_null($subschedule->report) ? $subschedule->report->sum('allocated_qty') : 0;
            $requestedAllocation = $additional['allocated_qty'];
            $subagentContractValue = $subschedule->subagent->contract_value;
            $daysInTheSchedule = $subschedule->order->schedule->daysIntervalUntilNextMonth();

            // If already + requested > contractValue * daysInterval, error will be inserted
            if ($alreadyAllocated + $requestedAllocation > $subagentContractValue * $daysInTheSchedule) {
                $v->errors()->add('allocated_qty', 'The requested allocation quantity ('. $requestedAllocation .') exceeds the subagent\'s monthly quota ('. ($subagentContractValue * $daysInTheSchedule) - $alreadyAllocated .' left). Please refer the documentation for more information.');
            }

        });

        return $validator;
    }

    /**
     * Run extra validation of complete request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterComplete(Validator $validator, $additional)
    {
    	if (   is_null($additional['sales_retailers']) 
            || is_null($additional['sales_household_qty']) 
            || is_null($additional['sales_microbusiness_qty']) 
            || is_null($additional['stock_empty_qty']) 
            || is_null($additional['stock_filled_qty'])) {
            return $validator;
        }

        $validator->after(function ($v) use ($additional) {

            // If the report has already been completed, error will be inserted
            // Obviously
            if (! is_null($additional['report']->reported_at)) {
                $v->errors()->add('report', 'The report has already been completed.');
            }

            // Get the requested report, sum of sales from retailers, sales from households, and sales from microbusinesses
            // Also get the empty and filled stock
            // All of these attributes are from "Pangkalan Logbook"
            // So technically "report" is actually a "logbook" (MIND -> BLOWN)
            $allocated = $additional['report']->allocated_qty;
            $salesRetailers = collect($additional['sales_retailers'])->sum('sales_qty');
            $salesRealization = $salesRetailers + $additional['sales_household_qty'] + $additional['sales_microbusiness_qty'];
            $stockRealization = $additional['stock_empty_qty'] + $additional['stock_filled_qty'];

            // If the allocated quantity is less than the total sales realization
            // Error will be inserted
            if ($allocated < $salesRealization) {
                $v->errors()->add('sales', 'The sum of requested sales ('. $salesRealization .') should not be bigger than the allocated quantity ('. $allocated .').');
            }

            // If the allocated quantity is less than the total stock realization
            // Error will be inserted
            if ($allocated < $stockRealization) {
                $v->errors()->add('stock', 'The sum of requested stock ('. $stockRealization .') should not be bigger than the allocated quantity ('. $allocated .').');
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
     * Add "allocation" field error to validator.
     * That's right! I separated this too due to its multiple calls.
     *
     * @param  Validator  $validator
     * @return void
     */
    protected function addAllocationError(Validator $validator)
    {
        $validator->errors()->add('allocation', 'The allocation field should be a pair of operator and value separated with colon (operator:value), where operator is valid according to documentation and value is not a negative integer');
    }    
}
