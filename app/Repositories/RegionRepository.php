<?php

namespace App\Repositories;

use App\Region;

class RegionRepository
{
    
    public function allWithQueries($inputs)
    {
        $query = Region::query()
        					->orderBy('id', 'asc');

        if (isset($inputs['province'])) {
        	$query->where('province_id', '=', $inputs['province']);
        }

        return $query->get();
    }
}
