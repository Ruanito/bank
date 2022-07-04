<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductControllerTest extends TestCase {
    private function mockSuccessRequest(): void {
        Http::fake([
            'https://api.stripe.com/v1/products' => Http::response([], 200, ['Headers']),
        ]);
    }

    private function mockFailRequest(): void {
        Http::fake([
            'https://api.stripe.com/v1/products' => Http::response([], 400, ['Headers']),
        ]);
    }

    public function test_createProduct(): void {
        $this->mockSuccessRequest();
        $expectedStatusCode = 201;
        $response = $this->call('POST', 'api/products');

        $response->assertStatus($expectedStatusCode);
    }

    public function test_couldNotCreateProduct(): void {
        $this->mockFailRequest();
        $expectedStatusCode = 400;
        $response = $this->call('POST', 'api/products');

        $response->assertStatus($expectedStatusCode);
    }
}
