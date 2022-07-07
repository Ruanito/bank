<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Internal\Stripe\Exception\StripePriceException;
use Internal\Stripe\Exception\StripeProductException;
use Internal\Stripe\Product\StripeProductService;

class ProductController {
    public function create(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'product.name' => 'required',
            'product.description' => 'required',
            'product.amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['status' => 'error', 'message' => $validator->getMessageBag()], 400);
        }

        try {
            $product =(new StripeProductService())->createProduct();
        } catch (StripeProductException|StripePriceException $e) {
            return response()
                ->json(['status' => 'error', 'message' => json_decode($e->getMessage())], 400);
        }

        return response()
            ->json(['status' => 'success', 'data' => $product->getAttributes()], 201);
    }
}
