<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Occupancy;
use App\Models\IssuedCard;
use Illuminate\Support\Facades\Config;

class OccupancyTest extends TestCase
{
    use RefreshDatabase;

    private $freeSpotsRoute = 'api/getFreeSpots';
    private $currentBillRoute = 'api/getCurrentBillAmount';
    private $checkoutRoute = 'api/checkoutVehicle';
    private $enterVehicleRoute = 'api/enterVehicle';

    private $cardNumber = '123123123';
    private $carNumber = '789789789';
    private $cardType = IssuedCard::CARD_TYPE_PLATINUM;

    private function prepareDB()
    {
        $createdCard = IssuedCard::create([
            'card_number' => $this->cardNumber,
            'card_type'   => $this->cardType,
            'is_valid'    => 1
        ]);

        Occupancy::create([
            'discount_card_id'  => $createdCard->id,
            'vehicle_reg_number' => $this->carNumber,
            'vehicle_type'      => 1,
            'occupied_at'       => now()->subDay()->format('Y-m-d H:i:s')
        ]);
    }

    public function test_get_free_spots_success()
    {
        $response = $this->getJson($this->freeSpotsRoute);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'message' => 'Available spots listed below are for either Type A(Car), Type B(Van) or Type C(Truck)',
                'spots' => [
                    'total_free_parking_spots' => 200,
                    'type_a' => 200,
                    'type_b' => 100,
                    'type_c' => 50
                ]
            ]
        ]);
    }

    public function test_check_current_bill_success()
    {
        $this->prepareDB();

        $response = $this->postJson($this->currentBillRoute, ['vehicle_reg_number' => $this->carNumber]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'amount'
            ]
        ]);
    }

    public function test_check_current_bill_invalid_number_failure()
    {
        $this->prepareDB();

        $response = $this->postJson($this->currentBillRoute, ['vehicle_reg_number' => '12312312']);

        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'vehicle_reg_number' => [
                    'The selected vehicle reg number is invalid.'
                ]
            ]
        ]);
    }

    public function test_register_car_with_discount_card_success()
    {
        $this->prepareDB();

        $response = $this->postJson($this->enterVehicleRoute, ['vehicle_reg_number' => '12312312', 'vehicle_type' => 1, 'card_number' => $this->cardNumber]);

        $response->assertStatus(201)->assertJson([
            'data' => [
                'registration_number' => '12312312',
                'entered_at'          => now()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    public function test_register_car_without_discount_card_success()
    {
        $response = $this->postJson($this->enterVehicleRoute, ['vehicle_reg_number' => '456456456', 'vehicle_type' => 1]);

        $response->assertStatus(201)->assertJson([
            'data' => [
                'registration_number' => '456456456',
                'entered_at'          => now()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    public function test_register_car_missing_type_failure()
    {
        $response = $this->postJson($this->enterVehicleRoute, ['vehicle_reg_number' => '12312312']);

        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'vehicle_type' => [
                    'The vehicle type field is required.'
                ]
            ]
        ]);
    }

    public function test_register_car_missing_vehicle_reg_number_failure()
    {
        $response = $this->postJson($this->enterVehicleRoute, ['vehicle_type' => 1]);

        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'vehicle_reg_number' => [
                    'The vehicle reg number field is required.'
                ]
            ]
        ]);
    }

    public function test_register_car_missing_all_failure()
    {
        $response = $this->postJson($this->enterVehicleRoute);

        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'vehicle_reg_number' => [
                    'The vehicle reg number field is required.'
                ],
                'vehicle_type' => [
                    'The vehicle type field is required.'
                ]
            ]
        ]);
    }

    public function test_checkout_car_success()
    {
        $this->prepareDB();
        $response = $this->postJson($this->checkoutRoute, ['vehicle_reg_number' => $this->carNumber]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'amount'
            ]
        ]);
    }

    public function test_checkout_car_failure()
    {
        $this->prepareDB();
        $response = $this->postJson($this->checkoutRoute, ['vehicle_reg_number' => 'wrong']);

        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'vehicle_reg_number' => [
                    'The selected vehicle reg number is invalid.'
                ]
            ]
        ]);
    }

    public function test_parking_lot_full_failure()
    {
        Config::set('app.max_parking_spots', 3); // set max spots to 3
        $response = $this->postJson($this->enterVehicleRoute, ['vehicle_reg_number' => '23123123', 'vehicle_type' => 3]); // veh type 3 requires 4 spots

        $response->assertStatus(403)->assertJson([
            'message' => 'No vacancy available',

        ]);
    }
}
