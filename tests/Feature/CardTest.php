<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    private $createCardRoute = 'api/issueCard';
    private $checkCardRoute = 'api/checkCardValidity';
    private $invalidateCardRoute = 'api/invalidateCard';

    public function test_create_discount_card_success()
    {
        $response = $this->postJson($this->createCardRoute, ['card_number' => '123123123', 'card_type' => 1]);

        $response->assertStatus(201)->assertJson([
            'data' => [
                'card_number'  => '123123123',
                'is_valid'  => 'Yes',
                'card_type'  => 'Silver card'
            ]
        ]);
    }

    public function test_create_discount_card_missing_type_failure()
    {
        $response = $this->postJson($this->createCardRoute, ['card_number' => 'another']);

        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'card_type' => [
                    'The card type field is required.'
                ]
            ]
        ]);
    }

    public function test_create_discount_card_missing_number_failure()
    {
        $response = $this->postJson($this->createCardRoute, ['card_type' => 1]);

        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'card_number' => [
                    'The card number field is required.'
                ]
            ]
        ]);
    }

    public function test_create_discount_card_incorrect_verb_type_failure()
    {
        $response = $this->getJson($this->createCardRoute, ['card_number' => '123123123']);

        $response->assertStatus(405)->assertJson([
            'message' => 'Method not allowed',
        ]);
    }

    public function test_check_discount_card_validity_success()
    {
        $creationResponse = $this->postJson($this->createCardRoute, ['card_number' => '123123123', 'card_type' => 1]);
        $validationResponse = $this->postJson($this->checkCardRoute, ['card_number' => '123123123']);

        $validationResponse->assertStatus(200)->assertJson([
            'data' => [
                'card_number' => '123123123',
                'is_valid'    => 'Yes',
                'card_type'   => 'Silver card'
            ]
        ]);
    }

    public function test_check_discount_card_validity_incorrect_number_failure()
    {
        $creationResponse = $this->postJson($this->createCardRoute, ['card_number' => '123123123', 'card_type' => 1]);
        $validationResponse = $this->postJson($this->checkCardRoute, ['card_number' => '1231123']);

        $validationResponse->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'card_number' => [
                    'The selected card number is invalid.'
                ]
            ]
        ]);
    }

    public function test_invalidate_discount_card_success()
    {
        $creationResponse = $this->postJson($this->createCardRoute, ['card_number' => '123123123', 'card_type' => 1]);
        $inValidationResponse = $this->postJson($this->invalidateCardRoute, ['card_number' => '123123123']);

        $inValidationResponse->assertStatus(200)->assertJson([
            'data' => [
                'card_number' => '123123123',
                'is_valid'    => 'No',
                'card_type'   => 'Silver card'
            ]
        ]);
    }

    public function test_invalidate_discount_card_wrong_number_failure()
    {
        $creationResponse = $this->postJson($this->createCardRoute, ['card_number' => '123123123', 'card_type' => 1]);
        $inValidationResponse = $this->postJson($this->invalidateCardRoute, ['card_number' => '1']);

        $inValidationResponse->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'card_number' => [
                    'The selected card number is invalid.'
                ]
            ]
        ]);
    }
}
