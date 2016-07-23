<?php

namespace App\Repositories;

use App\Models\Distribution;
use App\Models\Subdistribution;
use App\Repositories\Repository;
use Validator;

class SubdistributionRepository extends Repository
{
    /**
     * Get all of the subdistributions with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        //
    }

    /**
     * Store a new subdistribution model.
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
        //
    }

    /**
     * Mass assign subdistribution models.
     *
     * @param  array  $data
     * @return array
     */
    public function multiple($data)
    {
        $results = [];

        foreach ($data as $d) {
            $subdistribution = Subdistribution::create($d);
            $results[] = $this->extractResource($subdistribution, 'subdistributions');
        }

        return $results;
    }

    /**
     * Validate incoming subdistribution data.
     *
     * @param  array  $params
     * @return Validator
     */
    public function validate($params)
    {
        return Validator::make($params, [
            //
        ]);
    }
}
