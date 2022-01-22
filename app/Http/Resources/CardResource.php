<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'card_number' => $this->card_number,
            'is_valid'    => $this->is_valid ? 'Yes' : 'No',
            'card_type'   => $this->card_type
        ];
    }
}
