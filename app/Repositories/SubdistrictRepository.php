<?php

namespace App\Repositories;

use App\Subdistrict;

class SubdistrictRepository
{
    
    public function allWithQueries($inputs)
    {
        $query = Subdistrict::query()
        					->orderBy('id', 'asc');

        if (isset($inputs['district'])) {
        	$query->where('district_id', '=', $inputs['district']);
        }

        return $query->get();
    }
}
