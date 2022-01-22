<?php

namespace App\Http\Controllers;

use App\Http\Services\DiscountCardService;
use App\Http\Requests\CreateCardRequest;
use App\Http\Requests\CardDataRequest;
use App\Http\Resources\CardResource;


class DiscountCardController extends Controller
{
    private $cardService;
    public function __construct(DiscountCardService $service)
    {
        $this->cardService = $service;
    }

    public function issueCard(CreateCardRequest $request)
    {
        return new CardResource($this->cardService->store($request->validated()));
    }

    public function checkCardValidity(CardDataRequest $request)
    {
        return new CardResource($this->cardService->checkCardValidity($request->validated()));
    }

    public function invalidateCard(CardDataRequest $request)
    {
        return new CardResource($this->cardService->invalidateCard($request->validated()));
    }
}
