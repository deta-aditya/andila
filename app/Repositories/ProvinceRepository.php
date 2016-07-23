<?php

namespace App\Repositories;

use App\Province;

class ProvinceRepository
{
    
    public function all()
    {
        return Province::query()
                            ->orderBy('id', 'asc')
                            ->get();
    }
}
