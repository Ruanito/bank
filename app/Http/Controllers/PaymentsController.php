<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Internal\Stripe\Payment\StripePaymentService;

class PaymentsController extends Controller {
    public function create(Request $request): mixed {
        $validator = Validator::make($request->all(), [
            'payment' => 'required',
            'payment.*.price' => 'required',
            'payment.*.quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['status' => 'error', 'message' => $validator->getMessageBag()], 400);
        }

        $payment = (new StripePaymentService())->createPayment();
        return redirect()->away($payment->getRedirectUrl());
    }
}
