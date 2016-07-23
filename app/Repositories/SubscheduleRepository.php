<?php

namespace App\Repositories;

use App\Models\Subschedule;
use App\Models\Schedule;
use App\Repositories\Repository;
use App\Validations\SubscheduleValidation;
use Carbon\Carbon;
use Validator;

class SubscheduleRepository extends Repository
{
    /**
     * The station validation instance.
     *
     * @var SubscheduleValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  SubscheduleValidation  $validation
     * @return void
     */
    public function __construct(SubscheduleValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the subschedules with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        // By default each subschedules will load its own "report" data
        $query = $this->select(Subschedule::with('report.retailers'), $params, false);

        if ( array_has($params, 'schedule') ) {
            $query->ofSchedule($params['schedule']);
        }

        if ( array_has($params, 'order') ) {
            $query->ofOrder($params['order']);
        }

        if ( array_has($params, 'station') ) {
            $query->ofStation($params['station']);
        }

        // This is only restricted to admin LOL sorry
        if ( array_has($params, 'agent') && auth()->user()->isAdmin() ) {
            $query->ofAgent($params['agent']);
        }

        if ( array_has($params, 'subagent') ) {
            $query->ofSubagent($params['subagent']);
        }

        if ( array_has($params, 'this_month') ) {
            $query->ofThisMonth();
        }

        if ( array_has($params, 'this_week') ) {
            $query->ofThisWeek();
        }

        if ( array_has($params, 'allocated') ) {
            $query->allocated((bool)$params['allocated']);
        }

        if ( array_has($params, 'range') ) {
            $query->scheduledBetween(explode('_', $params['range']));
        }

        if (auth()->user()->isAgent()) {
            $query->ofAgent(auth()->user()->handleable->id);
        }

        if (auth()->user()->isSubagent()) {
            $query->ofSubagent(auth()->user()->handleable->id);
        }

        return $this->extractQuery($query, $params);
    }

	/**
     * Store multiple subschedule models at once.
     *
     * @param  array  $data
     * @return array
     */
    public function multiple($data)
    {
        $results = [];

        foreach ($data as $d) {
            $subschedule = Subschedule::create($d);        
            $results[] = $this->extractResource($subschedule, 'subschedules');
        }

        return $results;
    }

    /**
     * Get the specified subschedule model
     *
     * @param  Subschedule  $subschedule
     * @param  array  $params
     * @return array
     */
    public function show(Subschedule $subschedule, $params)
    {
        // By default it will load "report" method
        $subschedule->load('report.retailers');

        if ( array_has($params, 'order') ) {
            ! (bool)$params['order'] ?: $subschedule->load('order'); 
        }

        if ( array_has($params, 'subagent') ) {
            ! (bool)$params['subagent'] ?: $subschedule->load('subagent'); 
        }

        if ( array_has($params, 'agent') ) {
            ! (bool)$params['agent'] ?: $subschedule->load('subagent.agent'); 
        }

        if ( array_has($params, 'schedule') ) {
            ! (bool)$params['schedule'] ?: $subschedule->load('order.schedule'); 
        }

        return $this->extractResource($subschedule, 'subschedules');
    }
}
