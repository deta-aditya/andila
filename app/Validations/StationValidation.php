<?php

namespace App\Validations;

use App\Validations\Validation;

class StationValidation extends Validation
{
    /**
     * Get rules for index request.
     *
     * @return array
     */
    public function rulesIndex()
    {
        return array_merge($this->basicIndexRules('stations'), [
            'type' => 'sometimes|in:SPPBE,SPPEK,SPBU',
        ]);
    }

    /**
     * Get rules for store single request.
     *
     * @return array
     */
    public function rulesSingle()
    {
        return [
            'name' => 'required|max:255',
            'phone' => 'required|string',
            'location' => 'required|array',
            'location.*' => 'required|numeric',
            'type' => 'required|in:SPPBE,SPPEK,SPBU',
            'address.detail' => 'sometimes|string',
            'address.subdistrict' => 'sometimes|string',
            'address.district' => 'sometimes|string',
            'address.regency' => 'sometimes|string',
            'address.province' => 'sometimes|string',
            'address.postal_code' => 'sometimes|numeric',
        ];
    }

    /**
     * Get rules for show request.
     *
     * @return array
     */
    public function rulesShow()
    {
        return [
            'schedules' => 'sometimes|boolean',
            'agents' => 'sometimes|boolean',
        ];
    }

    /**
     * Get rules for update request.
     *
     * @return array
     */
    public function rulesUpdate()
    {
        return [
            'name' => 'sometimes|max:255',
            'phone' => 'sometimes|string',
            'location' => 'sometimes|array',
            'location.*' => 'required_with:location|numeric',
            'type' => 'sometimes|in:SPPBE,SPPEK,SPBU',
            'address.detail' => 'sometimes|string',
            'address.subdistrict' => 'sometimes|string',
            'address.district' => 'sometimes|string',
            'address.regency' => 'sometimes|string',
            'address.province' => 'sometimes|string',
            'address.postal_code' => 'sometimes|numeric',
        ];
    }
}
