<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Models\VehicleRate;
use App\Models\IssuedCard;
use App\Models\Occupancy;
use Carbon\Carbon;

class CalculationService extends Controller
{
    public function calculateAmount(array $attributes)
    {
        $vehicleService = new VehicleService();
        $occupancyService = new OccupancyService();
        $occupancyRecord = Occupancy::where('vehicle_reg_number', $attributes['vehicle_reg_number'])->first();

        $rates = $vehicleService->getRatesForType($occupancyRecord->vehicle_type);
        $hoursArray = $this->getFullHoursToNow($occupancyRecord->occupied_at);

        $amount = $this->getAmountForHours($hoursArray, $rates);

        if (!is_null($occupancyRecord->discountCard)) {
            $amount = $this->applyDiscount($amount, $occupancyRecord->discountCard);
        }

        if (isset($attributes['checkout'])) {
           $occupancyService->checkoutVehicle($occupancyRecord->id);
        }

        return $amount;
    }

    public function getFullHoursToNow(Carbon $date)
    {
        $numberOfHours = ceil($date->startOfHour()->floatDiffInHours(now()));

        $dayHours = 0;
        $nightHours = 0;

        for ($i = 0; $i < $numberOfHours; $i++) {
            if ($date->addHour()->between(Carbon::create($date->format('Y-m-d').'08:00:00'), Carbon::create($date->format('Y-m-d').'18:00:00'))) {
                $dayHours += 1;
            } else {
                $nightHours += 1;
            }
        }

        return ['night_hours' => $nightHours, 'day_hours' => $dayHours];
    }

    public function getAmountForHours(array $hoursArray, VehicleRate $vehicleRateObject)
    {
        $amount = 0;
        $amount += $hoursArray['night_hours'] * $vehicleRateObject->night_rate;
        $amount += $hoursArray['day_hours'] * $vehicleRateObject->day_rate;

        return $amount;
    }

    public function applyDiscount($amount, IssuedCard $card)
    {
        return $amount -= $amount * $card->getDiscountRatesByCard()[$card->getAttributes()['card_type']];
    }
}
