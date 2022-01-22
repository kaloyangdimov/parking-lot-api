<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Models\IssuedCard;

class DiscountCardService extends Controller
{
    public function store(array $attributes)
    {
        return IssuedCard::create(array_merge(['is_valid' => true], $attributes));
    }

    public function checkCardValidity(array $attributes)
    {
        return IssuedCard::where('card_number', $attributes['card_number'])->where('is_valid', true)->first();
    }

    public function invalidateCard(array $attributes)
    {
        $card = IssuedCard::where('card_number', $attributes['card_number'])->first();
        $card->is_valid = false;
        $card->save();

        return $card;
    }
}
