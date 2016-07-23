<?php

namespace App\Repositories;

use App\Models\Distribution;
use App\Repositories\Repository;
use App\Events\DistributionWasPlanned;
use Validator;

class DistributionRepository extends Repository
{
    /**
     * Get all of the distributions with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        $query = $this->select(Distribution::with('agent', 'station'), $params, false);

        if ( array_has($params, 'station') ) {
            $query->where('station_id', $params['station']);
        }

        if ( array_has($params, 'agent') ) {
            $query->where('agent_id', $params['agent']);
        }

        if ( array_has($params, 'date_planned') ) {
            $range = explode('_', $params['date_planned']);
            $query->plannedBetween($range[0], $range[1]);
        }

        if ( array_has($params, 'date_shipped') ) {
            $range = explode('_', $params['date_shipped']);
            $query->shippedBetween($range[0], $range[1]);
        }

        if ( array_has($params, 'reported_at') ) {
            $range = explode('_', $params['reported_at']);
            $query->reportedBetween($range[0], $range[1]);
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Store a new distribution model.
     *
     * @param  int  $station_id
     * @param  int  $agent_id
     * @param  int|null  $allocation
     * @param  string|null  $date_planned
     * @param  string|null  $date_shipped
     * @param  string|null  $reported_at
     * @return array
     */
    public function single($station_id, $agent_id, $allocation = null, $date_planned = null, $date_shipped = null, $reported_at = null)
    {
        $distribution = new Distribution;

        $distribution->station_id = $station_id;
        $distribution->agent_id = $agent_id;
        $distribution->allocation = $allocation;
        $distribution->date_planned = $date_planned;
        $distribution->date_shipped = $date_shipped;
        $distribution->reported_at = $reported_at;

        $distribution->save();

        if (! is_null($distribution->date_planned)) {
            event(new DistributionWasPlanned($distribution));
        }

        return $this->extractResource($distribution, 'distributions');
    }

    /**
     * Mass assign distribution models.
     *
     * @param  array  $data
     * @return array
     */
    public function multiple($data)
    {
        $results = [];

        foreach ($data as $d) {
            $distribution = Distribution::create($d);

            if (! is_null($distribution->date_planned)) {
                event(new DistributionWasPlanned($distribution));
            }
        
            $results[] = $this->extractResource($distribution, 'distributions');
        }

        return $results;
    }

    /**
     * Validate incoming distribution data.
     *
     * @param  array  $params
     * @return Validator
     */
    public function validate($params)
    {
        return Validator::make($params, [
            'station_id' => 'required|exists:andila.stations,id',
            'agent_id' => 'required|exists:andila.agents,id',
            'allocation' => 'sometimes|numeric|between:1000,100000|required_with_all:station_id,agent_id',
            'date_planned' => 'sometimes|date_format:Y-m-d|required_with:allocation',
            'date_shipped' => 'sometimes|date_format:Y-m-d|required_with:date_planned',
            'reported_at' => 'sometimes|date_format:Y-m-d H:i:s|required_with:date_shipped',
        ]);
    }
}
