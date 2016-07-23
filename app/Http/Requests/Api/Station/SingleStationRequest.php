<?php

namespace App\Http\Requests\Api\Station;

use App\Http\Requests\Request;

class SingleStationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric',
            'location' => 'required|array',
            'type' => 'required|in:SPPBE,SPPEK,SPBU',
            'address.street' => 'sometimes|string',
            'address.subdistrict' => 'sometimes|string|not_in:null',
            'address.district' => 'sometimes|string|not_in:null',
            'address.regency' => 'sometimes|string|not_in:null',
            'address.province' => 'sometimes|string|not_in:null',
            'address.postal_code' => 'sometimes|numeric',
        ];
    }
}
