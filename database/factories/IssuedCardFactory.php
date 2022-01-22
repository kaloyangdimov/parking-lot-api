<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IssuedCard;

class IssuedCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'card_number' => $this->faker->uuid(),
            'is_valid'    => 1,
            'card_type'   => $this->faker->randomElement([1,2,3])
        ];
    }
}
