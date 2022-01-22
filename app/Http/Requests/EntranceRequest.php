<?php

namespace App\Http\Requests;

use App\Models\VehicleRate;
use Illuminate\Foundation\Http\FormRequest;

class EntranceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vehicle_reg_number' => 'required|string|unique:occupancy,vehicle_reg_number',
            'card_number'        => 'nullable|string',
            'vehicle_type'       => 'required|integer|in:'. implode(',', array_keys(VehicleRate::getVehicleSizes())),
        ];
    }
}
