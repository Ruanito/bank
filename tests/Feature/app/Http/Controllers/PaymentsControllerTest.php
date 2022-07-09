<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentsControllerTest extends TestCase {
    private function mockSuccessRequest(): void {
        Http::fake([
            'https://api.stripe.com/v1/products' => Http::response([
                'url' => 'https://google.com',
            ], 200, ['Headers']),
        ]);
    }

    public function test_shouldCreatePayment() {
        $this->mockSuccessRequest();
        $expectedStatusCode = 302;
        $response = $this->call('POST', 'api/payments', [
            'payment' => [
                ['price' => 'price', 'quantity' => 1],
            ],
        ]);

        $response->assertStatus($expectedStatusCode);
    }

    public function test_shouldNotCreateWithoutParameters() {
        $this->mockSuccessRequest();
        $expectedStatusCode = 400;
        $response = $this->call('POST', 'api/payments', ['payment' => [[]]]);

        $response->assertStatus($expectedStatusCode);
        $response->assertJson([
            'status' => 'error',
            'message' => [
                'payment.0.price' => ['The payment.0.price field is required.'],
                'payment.0.quantity' => ['The payment.0.quantity field is required.'],
            ],
        ]);
    }
}
