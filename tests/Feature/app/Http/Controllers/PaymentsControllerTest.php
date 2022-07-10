<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentsControllerTest extends TestCase {
    private function mockSuccessRequest() {
        Http::fake([
            'https://api.stripe.com/v1/payment_links' => Http::response([
                'url' => 'https://google.com',
            ], 200, ['Headers']),
        ]);
    }

    private function mockInvalidRequest() {
        Http::fake([
            'https://api.stripe.com/v1/payment_links' => Http::response([
                'Invalid request',
            ], 400, ['Headers']),
        ]);
    }

    public function test_shouldCreatePayment() {
        $this->mockSuccessRequest();
        $expectedStatusCode = 302;
        $response = $this->call('POST', 'api/payments', [
            'items' => [
                ['price' => 'price', 'quantity' => 1],
            ],
        ]);

        $response->assertStatus($expectedStatusCode);
    }

    public function test_shouldNotCreateWithoutParameters() {
        $this->mockSuccessRequest();
        $expectedStatusCode = 400;
        $response = $this->call('POST', 'api/payments', ['items' => [[]]]);

        $response->assertStatus($expectedStatusCode);
        $response->assertJson([
            'status' => 'error',
            'message' => [
                'items.0.price' => ['The items.0.price field is required.'],
                'items.0.quantity' => ['The items.0.quantity field is required.'],
            ],
        ]);
    }

    public function test_shouldNotCreatePaymentWhenStripeReturnsBadRequest() {
        $this->mockInvalidRequest();
        $expectedStatusCode = 400;
        $response = $this->call('POST', 'api/payments', [
            'items' => [
                ['price' => 'price', 'quantity' => 1],
            ],
        ]);

        $response->assertStatus($expectedStatusCode);
        $response->assertJson([
            'status' => 'error',
            'message' => ['Invalid request'],
        ]);
    }
}
