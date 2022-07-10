<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Internal\Stripe\Exception\StripePaymentException;
use Internal\Stripe\Payment\StripePaymentService;

class PaymentsController extends Controller {
    public function create(Request $request): mixed {
        $validator = Validator::make($request->all(), [
            'items' => 'required',
            'items.*.price' => 'required',
            'items.*.quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['status' => 'error', 'message' => $validator->getMessageBag()], 400);
        }

        try {
            $payment = (new StripePaymentService())->create($request->input('items'));
            return redirect()->away($payment->getRedirectUrl());
        } catch (StripePaymentException $e) {
            return response()
                ->json(['status' => 'error', 'message' => json_decode($e->getMessage())], 400);
        }
    }
}
