<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BalanceControllerTest extends TestCase {
    private function mockSuccessRequest() {
        Http::fake([
            'https://api.stripe.com/v1/balance' => Http::response([
                'available' => [
                    ['amount' => 10000, 'currency' => 'brl']
                ],
            ], 200, ['Headers']),
        ]);
    }

    public function test_shouldReturnBalance() {
        $this->mockSuccessRequest();
        $expectedStatusCode = 200;
        $response = $this->call('GET', 'api/balance');

        $response->assertStatus($expectedStatusCode);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'amount' => 10000,
                'currency' => 'brl',
            ],
        ]);
    }
}
