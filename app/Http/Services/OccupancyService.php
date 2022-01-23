<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Models\Occupancy;
use App\Models\VehicleRate;

class OccupancyService extends Controller
{
    public function getFreeSpots()
    {
        $vehiclesInUse = Occupancy::all();

        $occupiedCount = 0;

        $sizes = VehicleRate::getVehicleSizes();

        foreach($vehiclesInUse as $singleVehicle) {
            $occupiedCount += $sizes[$singleVehicle->vehicle_type];
        }

        $availableSpots = config('app.max_parking_spots') - $occupiedCount;

        return [
            'total_free_parking_spots' => $this->getSpotsBySize($availableSpots, VehicleRate::SIZE_A),
            'type_a' => $this->getSpotsBySize($availableSpots, VehicleRate::SIZE_A),
            'type_b' => $this->getSpotsBySize($availableSpots, VehicleRate::SIZE_B),
            'type_c' => $this->getSpotsBySize($availableSpots, VehicleRate::SIZE_C)
        ];
    }

    private function getSpotsBySize(int $availableSpots, int $size)
    {
        return floor($availableSpots/$size);
    }

    public function checkoutVehicle(int $id)
    {
        Occupancy::find($id)->delete();
    }

    public function enterVehicle(array $attributes)
    {
        $occupancyRecord = new Occupancy();

        $occupancyRecord->vehicle_reg_number = $attributes['vehicle_reg_number'];
        $occupancyRecord->vehicle_type = $attributes['vehicle_type'];

        if (isset($attributes['discount_card_id'])) {
            $occupancyRecord->discount_card_id = $attributes['discount_card_id'];
        }

        $occupancyRecord->occupied_at = now()->format('Y-m-d H:i:s');

        $occupancyRecord->save();

        return $occupancyRecord;
    }
}
