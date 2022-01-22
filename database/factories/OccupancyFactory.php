<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IssuedCard;
use App\Models\VehicleRate;

class OccupancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vehicle_reg_number' => $this->faker->uuid(),
            'discount_card_id'   => $this->faker->optional()->randomElement(IssuedCard::all()->pluck('id')),
            'vehicle_type'       => $this->faker->randomElement(VehicleRate::all()->pluck('type')),
            'occupied_at'        => now()->subDay(3)->format('Y-m-d H:i:s')
        ];
    }
}
