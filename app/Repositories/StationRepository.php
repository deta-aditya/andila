<?php

namespace App\Repositories;

use App\Models\Station;
use App\Validations\StationValidation;
use App\Repositories\Repository;

class StationRepository extends Repository
{
    /**
     * The station validation instance.
     *
     * @var StationValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  StationValidation  $validation
     * @return void
     */
    public function __construct(StationValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the stations with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        $query = $this->select(Station::with('address'), $params, false);

        if ( array_has($params, 'type') ) {
            ! (bool)$params['type'] ?: $query->ofType($params['type']);
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Store a new station model.
     *
     * @param  array  $data
     * @return array
     */
    public function single($data)
    {
        $stationData = array_only($data, ['name', 'phone', 'location', 'type']);

        $station = new Station($stationData);
        $station->save();

        if ( array_has($data, 'address') ) {
            $station->address()->create($data['address']);
            $station->save();
            $station->load('address');
        }

        return $this->extractResource($station, 'stations');
    }

    /**
     * Get the specified station model
     *
     * @param  Station  $station
     * @param  array  $params
     * @return array
     */
    public function show(Station $station, $params)
    {
        // By default it will load "address" method
        $station->load('address');

        if ( array_has($params, 'schedules') ) {
            ! (bool)$params['schedules'] ?: $station->load('schedules.order'); 
        }

        if ( array_has($params, 'agents') ) {
            ! (bool)$params['agents'] ?: $station->load('agents'); 
        }

        return $this->extractResource($station, 'stations');
    }

    /**
     * Update the specified station model.
     *
     * @param  Station  $station
     * @param  array  $params
     * @return array
     */
    public function update(Station $station, $params)
    {
        $stationData = array_only($params, ['name', 'phone', 'location', 'type']);

        $station->update($stationData);
        
        if ( array_has($params, 'address') ) {
            $station->address()->updateOrCreate($params['address']);
            $station->save();
            $station->load('address');
        }

        return $this->extractResource($station, 'stations');
    }

    /**
     * Delete the specified station model.
     *
     * @param  station  $station
     * @return array
     */
    public function destroy(station $station)
    {
        $station->delete();
        $station->address()->delete();
        return $this->extractResource($station, 'stations');
    }
}
