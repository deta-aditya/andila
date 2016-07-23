<?php

namespace App\Repositories;

use App\Models\Indonesia\Province;
use App\Models\Indonesia\Regency;
use App\Models\Indonesia\District;
use App\Models\Indonesia\Subdistrict;
use App\Validations\IndonesiaValidation;
use App\Repositories\Repository;

class IndonesiaRepository extends Repository
{
	/**
     * The indonesia validation instance.
     *
     * @var IndonesiaValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  IndonesiaValidation  $validation
     * @return void
     */
    public function __construct(IndonesiaValidation $validation)
    {
        $this->validation = $validation;
    }

	/**
     * Get all of the provinces with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function provinces($params)
    {
        return $this->extractQuery(Province::query(), $params);
    }

	/**
     * Get all of the regencies with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function regencies($params)
    {
        $query = Regency::query();

        if ( array_has($params, 'province') ) {
            $query->ofProvince($params['province']);
        }

        return $this->extractQuery($query, $params);
    }

	/**
     * Get all of the districts with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function districts($params)
    {
        $query = District::query();

        if ( array_has($params, 'regency') ) {
            $query->ofRegency($params['regency']);
        }

        return $this->extractQuery($query, $params);
    }

	/**
     * Get all of the subdistricts with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function subdistricts($params)
    {
        $query = Subdistrict::query();

        if ( array_has($params, 'district') ) {
            $query->ofDistrict($params['district']);
        }

        return $this->extractQuery($query, $params);
    }
}
