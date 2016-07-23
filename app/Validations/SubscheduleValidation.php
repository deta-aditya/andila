<?php

namespace App\Validations;

use App\Validations\Validation;
use Illuminate\Validation\Validator;

class SubscheduleValidation extends Validation
{
	/**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('subschedules'), [
            'schedule' => 'sometimes|exists:andila.schedules,id',
            'order' => 'sometimes|exists:andila.orders,id',
            'station' => 'sometimes|exists:andila.stations,id',
            'agent' => 'sometimes|exists:andila.agents,id',
            'subagent' => 'sometimes|exists:andila.subagents,id',
            'this_month' => 'sometimes|boolean',
            'this_week' => 'sometimes|boolean',
            'allocated' => 'sometimes|boolean',
            'range' => 'sometimes',
        ]);
    }

    /**
     * Get rules for show request.
     *
     * @return array
     */
    public function rulesShow()
    {
        return [
            'order' => 'sometimes|boolean',
            'subagent' => 'sometimes|boolean',
            'agent' => 'sometimes|boolean',
            'schedule' => 'sometimes|boolean',
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
        if (is_null($additional['range'])) {
            return $validator;
        }

        $validator->after(function ($v) use ($additional) {
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
}
