<?php

namespace App\Validations;

use App\Models\Agent;
use App\Validations\Validation;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class ScheduleValidation extends Validation
{
    /**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('schedules'), [
            'station' => 'sometimes|exists:andila.stations,id',
            'agent' => 'sometimes|exists:andila.agents,id',
            'this_month' => 'sometimes|boolean',
            'ordered' => 'sometimes|boolean',
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
            'station_id' => 'required|exists:andila.stations,id',
            'agent_id' => 'required|exists:andila.agents,id',
            'scheduled_date' => 'required|date_format:Y-m-d',
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
            'station' => 'sometimes|boolean',
            'agent' => 'sometimes|boolean',
            'subschedules' => 'sometimes|boolean',
        ];
    }

    /**
     * Get rules for destroy request.
     *
     * @return array
     */
    public function rulesDestroy()
    {
        // Some of you may wonder why the rule is empty.
        // The thing is, we still need this method to return an array
        // in order to enter the "after validation" method,
        // which is the core validation part of destroy request.
        return [
            //
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
     * Run extra validation of single request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterSingle(Validator $validator, $additional)
    {
        if (is_null($additional['agent_id']) || is_null($additional['scheduled_date'])) {
            return $validator;
        }

        $validator->after(function ($v) use ($additional) {

            // If the requested agent is not "schedulable", error will be inserted
            // Schedulable here means that the agent should be in the active state and supervises at least one subagent
            if (Agent::schedulable()->where('id', $additional['agent_id'])->count() < 1) {
                $v->errors()->add('agent_id', 'The requested agent ID is not schedulable, which means it is not active nor having any subagent.');
            }

            // Get the current schedule of requested agent.
            // Current schedule is identified by its scheduled date's year and month
            $current = Agent::find($additional['agent_id'])->schedules()->ofThisMonth()->first();
            $requestedDate = Carbon::parse($additional['scheduled_date']);

            // Bottom limit is either a month after the current schedule or any day after today.
            $bottomLimit = (! is_null($current))
                ? Carbon::parse($current->scheduled_date)->addMonth()->subDay()
                : Carbon::now();

            // If the bottom limit is above the requested date
            // Error will be inserted
            if ($bottomLimit->gt($requestedDate)) {
                $v->errors()->add('scheduled_date', 'The requested scheduled date should be after '. $bottomLimit->toDateString() .', which is at least a month from the current schedule, or if does not exist, after today.');
            }

        });

        return $validator;
    }

    /**
     * Run extra validation of destroy request.
     *
     * @param  Validator  $validator
     * @param  array  $additional
     * @return Validator
     */
    public function afterDestroy(Validator $validator, $additional)
    {
        $validator->after(function ($v) use ($additional) {

            // If the schedule is unmodifyable, error will be inserted
            // So please be careful when creating a schedule!
            if (! $additional['schedule']->isModifyable()) {
                $v->errors()->add('schedule', 'The requested schedule is not modifyable. Please refer to the documentation for more information.');
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
