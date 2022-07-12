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
                'id' => 'price_id',
                'currency' => 'brl',
                'unit_amount' => 20000,
            ], 200, ['Headers']),
        ]);
    }

    private function mockFailRequest(): void {
        Http::fake([
            'https://api.stripe.com/v1/products' => Http::response(['Invalid request'], 400, ['Headers']),
        ]);
    }

    public function test_createProduct(): void {
        $this->mockSuccessRequest();
        $expectedStatusCode = 201;
        $response = $this->call('POST', 'api/products', [
            'product' => [
                'name' => 'name',
                'description' => 'description',
                'amount' => 10
            ],
        ]);

        $response->assertStatus($expectedStatusCode);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'product_id' => 'product_id',
                'name' => 'name',
                'description' => 'description',
                'external_reference' => 'price_id',
                'currency' => 'brl',
                'amount' => 20000
            ],
        ]);
    }

    public function test_couldNotCreateProduct(): void {
        $this->mockFailRequest();
        $expectedStatusCode = 400;
        $response = $this->call('POST', 'api/products', [
            'product' => [
                'name' => 'name',
                'description' => 'description',
                'amount' => 10
            ],
        ]);

        $response->assertStatus($expectedStatusCode);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Could not create a PaymentLink',
        ]);
    }

    public function test_couldNotCreateProductWithInvalidParameters(): void {
        $this->mockFailRequest();
        $expectedStatusCode = 400;
        $response = $this->call('POST', 'api/products');

        $response->assertStatus($expectedStatusCode);
        $response->assertJson([
            'status' => 'error',
            'message' => [
                'product.name' => ['The product.name field is required.'],
                'product.description' => ['The product.description field is required.'],
                'product.amount' => ['The product.amount field is required.']
            ],
        ]);
    }
}
