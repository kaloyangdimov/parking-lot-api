<?php

namespace App\Http\Requests;

use App\Models\IssuedCard;
use Illuminate\Foundation\Http\FormRequest;

class CreateCardRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_number' => 'required|string|unique:issued_cards,card_number',
            'card_type'   => 'required|integer|in:'. IssuedCard::CARD_TYPE_GOLD.', '.IssuedCard::CARD_TYPE_SILVER.', '.IssuedCard::CARD_TYPE_PLATINUM,
        ];
    }
}
