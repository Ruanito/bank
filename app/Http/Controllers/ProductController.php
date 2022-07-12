<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as RequestValidator;
use Internal\Bank\Product\BankProductRequest;
use Internal\Stripe\Exception\StripePriceException;
use Internal\Stripe\Exception\StripeProductException;
use Internal\Stripe\Product\StripeProductService;

class ProductController {
    public function create(Request $request): JsonResponse {
        $validator = $this->getValidator($request);
        if ($validator->fails()) {
            return response()
                ->json(['status' => 'error', 'message' => $validator->getMessageBag()], 400);
        }

        try {
            $params = $this->getParams($request);
            $product =(new StripeProductService($params))->create();
        } catch (StripeProductException|StripePriceException $e) {
            return response()
                ->json(['status' => 'error', 'message' => json_decode($e->getMessage())], 400);
        }

        return response()
            ->json(['status' => 'success', 'data' => $product->getAttributes()], 201);
    }

    private function getParams(Request $request): BankProductRequest {
        return BankProductRequest::build()
            ->withName($request->input('product.name'))
            ->withDescription($request->input('product.description'))
            ->withAmount($request->input('product.amount'))
            ->builder();
    }

    private function getValidator(Request $request): RequestValidator {
        return Validator::make($request->all(), [
            'product.name' => 'required',
            'product.description' => 'required',
            'product.amount' => 'required|numeric',
        ]);
    }
}
