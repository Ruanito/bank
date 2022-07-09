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

    public function test_createPayment() {
        $this->mockSuccessRequest();
        $expectedStatusCode = 302;
        $response = $this->call('POST', 'api/payments');

        $response->assertStatus($expectedStatusCode);
    }
}
