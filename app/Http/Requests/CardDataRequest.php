<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardDataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_number' => 'required|string|exists:issued_cards,card_number',
        ];
    }
}
