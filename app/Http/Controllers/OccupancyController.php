<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrentBillAmountRequest;
use App\Http\Resources\EnteredVehicleResource;
use App\Http\Services\DiscountCardService;
use App\Http\Services\CalculationService;
use App\Http\Resources\FreeSpotsResource;
use App\Http\Resources\MonetaryResource;
use App\Http\Services\OccupancyService;
use App\Http\Requests\EntranceRequest;
use App\Models\VehicleRate;

class OccupancyController extends Controller
{
    private $occupancyService;
    private $calculationService;

    public function __construct(OccupancyService $occupancyService, CalculationService $calculationService)
    {
        $this->occupancyService = $occupancyService;
        $this->calculationService = $calculationService;
    }

    public function getFreeSpots()
    {
        return new FreeSpotsResource($this->occupancyService->getFreeSpots([]));
    }

    public function getCurrentBillAmount(CurrentBillAmountRequest $request)
    {
        return new MonetaryResource($this->calculationService->calculateAmount($request->validated()));
    }

    public function checkoutVehicle(CurrentBillAmountRequest $request)
    {
        return new MonetaryResource($this->calculationService->calculateAmount(array_merge(['checkout' => true], $request->validated())));
    }

    public function enterVehicle(EntranceRequest $request)
    {
        $cardService = new DiscountCardService();
        $freeSpots = $this->occupancyService->getFreeSpots();
        $vehicleSize = VehicleRate::getVehicleSizes()[$request->vehicle_type];

        if (($freeSpots['total_free_parking_spots'] / $vehicleSize) < 1) {
            return response()->json(['message' => 'No vacancy available'], 403);
        }

        $requestData = $request->validated();
        if ($request->card_number) {
            $card = $cardService->checkCardValidity($request->validated());

            if (!is_null($card)) {
                $requestData = array_merge(
                    $requestData,
                    ['discount_card_id' => $card->id]
                );
            }
        }

        return new EnteredVehicleResource($this->occupancyService->enterVehicle(
            $requestData
        ));
    }
}
