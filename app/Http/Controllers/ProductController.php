<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Internal\Stripe\Exception\StripePriceException;
use Internal\Stripe\Exception\StripeProductException;
use Internal\Stripe\Product\StripeProductService;

class ProductController {
    public function create(Request $request): JsonResponse {
        try {
            $product =(new StripeProductService())->createProduct();
        } catch (StripeProductException|StripePriceException $e) {
            return response()
                ->json(['status' => 'error', 'data' => json_decode($e->getMessage())], 400);
        }

        return response()
            ->json(['status' => 'success', 'data' => $product->getAttributes()], 201);
    }
}
