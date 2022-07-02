<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Internal\Stripe\Payment\StripePaymentService;

class PaymentsController extends Controller {
    public function create(): RedirectResponse {
        $payment = (new StripePaymentService())->createPayment();
        return redirect()->away($payment->getRedirectUrl());
    }
}
