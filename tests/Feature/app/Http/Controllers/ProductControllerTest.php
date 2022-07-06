<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductControllerTest extends TestCase {
    private function mockSuccessRequest(): void {
        Http::fake([
            'https://api.stripe.com/v1/products' => Http::response([
                'id' => 'product_id',
                'name' => 'name',
                'description' => 'description',
            ], 200, ['Headers']),
        ]);
        Http::fake([
            'https://api.stripe.com/v1/prices' => Http::response([
                'id' => 'id',
                'name' => 'name',
                'description' => 'description',
            ], 200, ['Headers']),
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
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'product_id' => 'product_id',
                'name' => 'name',
                'description' => 'description',
            ],
        ]);
    }

    public function test_couldNotCreateProduct(): void {
        $this->mockFailRequest();
        $expectedStatusCode = 400;
        $response = $this->call('POST', 'api/products');

        $response->assertStatus($expectedStatusCode);
    }
}
