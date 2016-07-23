<?php

namespace App\Repositories;

use App\District;

class DistrictRepository
{
    
    public function allWithQueries($inputs)
    {
        $query = District::query()
        					->orderBy('id', 'asc');

        if (isset($inputs['region'])) {
        	$query->where('region_id', '=', $inputs['region']);
        }

        return $query->get();
    }
}
