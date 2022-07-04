<?php

namespace Tests\Feature\app\Http\Controllers;

use Tests\TestCase;

class ProductControllerTest extends TestCase {
    public function test_create_product() {
        $expectedStatusCode = 201;
        $response = $this->call('POST', 'api/products');

        $response->assertStatus($expectedStatusCode);
    }
}
