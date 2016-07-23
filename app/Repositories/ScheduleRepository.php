<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Models\Station;
use App\Models\Agent;
use App\Validations\ScheduleValidation;
use App\Repositories\Repository;
use Carbon\Carbon;
use Validator;

class ScheduleRepository extends Repository
{
    /**
     * The station validation instance.
     *
     * @var ScheduleValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  ScheduleValidation  $validation
     * @return void
     */
    public function __construct(ScheduleValidation $validation)
    {
        $this->validation = $validation;
    }

	/**
     * Get all of the schedule with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        // By default each schedules will load its own "order" data
    	$query = $this->select(Schedule::with('order'), $params, false);

        if ( array_has($params, 'station') ) {
            $query->ofStation($params['station']);
        }

        // This is only restricted to admin LOL sorry
        if ( array_has($params, 'agent') && auth()->user()->isAdmin() ) {
            $query->ofAgent($params['agent']);
        }

        if ( array_has($params, 'this_month') ) {
            $query->ofThisMonth();
        }

        if ( array_has($params, 'ordered') ) {
            $query->ordered((bool)$params['ordered']);
        }

        if ( array_has($params, 'range') ) {
            $query->scheduledBetween(explode('_', $params['range']));
        }

        if (auth()->user()->isAgent()) {
            $query->ofAgent(auth()->user()->handleable->id);
        }

        return $this->extractQuery($query, $params);
    }

	/**
     * Store a new schedule model.
     *
     * @param  array  $data
     * @return array
     */
    public function single($data)
    {
        $scheduleData = array_only($data, ['station_id', 'agent_id', 'scheduled_date']);

        $schedule = new Schedule($scheduleData);
        $schedule->save();

        Agent::find($data['agent_id'])->schedules()->save($schedule);
        Station::find($data['station_id'])->schedules()->save($schedule);

        return $this->extractResource($schedule, 'schedules');
    }

    /**
     * Store multiple schedule models at once.
     *
     * @param  array  $data
     * @return array
     */
    public function multiple($data)
    {
        $results = [];

        foreach ($data as $d) {            
            $results[] = $this->single($d);
        }

        return $results;
    }

    /**
     * Get the specified schedule model
     *
     * @param  Schedule  $schedule
     * @param  array  $params
     * @return array
     */
    public function show(Schedule $schedule, $params)
    {
        // By default it will load "order" method
        $schedule->load('order');

        if ( array_has($params, 'station') ) {
            ! (bool)$params['station'] ?: $schedule->load('station'); 
        }

        if ( array_has($params, 'agent') ) {
            ! (bool)$params['agent'] ?: $schedule->load('agent'); 
        }

        if ( array_has($params, 'subschedules') ) {
            ! (bool)$params['subschedules'] ?: $schedule->load('subschedules'); 
        }

        return $this->extractResource($schedule, 'schedules');
    }

    /**
     * Delete the specified schedule model.
     *
     * @param  Schedule  $schedule
     * @return array
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return $this->extractResource($schedule, 'schedules');
    }
}
