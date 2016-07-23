<?php

namespace App\Validations;

use App\Validations\Validation;

class IndonesiaValidation extends Validation
{
    /**
     * Get rules for province request.
     *
     * @return array
     */
    public function rulesProvince()
    {
        return [
            //
        ];
    }
    /**
     * Get rules for regency request.
     *
     * @return array
     */
    public function rulesRegency()
    {
        return [
            'province' => 'exists:indonesia.provinces,id',
        ];
    }
    /**
     * Get rules for district request.
     *
     * @return array
     */
    public function rulesDistrict()
    {
        return [
            'regency' => 'exists:indonesia.regencies,id',
        ];
    }
	/**
     * Get rules for subdistrict request.
     *
     * @return array
     */
    public function rulesSubdistrict()
    {
        return [
            'district' => 'exists:indonesia.districts,id',
        ];
    }
}
