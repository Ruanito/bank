<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Internal\Stripe\Exception\StripeProductException;
use Internal\Stripe\Product\StripeProductService;

class ProductController {
    public function create(Request $request): JsonResponse {
        try {
            (new StripeProductService())->createProduct();
        } catch (StripeProductException $e) {
            return response()
                ->json(['status' => 'error', 'data' => json_decode($e->getMessage())], 400);
        }

        return response()
            ->json(['status' => 'success'], 201);
    }
}
