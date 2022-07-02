<?php

namespace Internal\Stripe\Payment;

use Internal\Bank\PaymentResponse;

class StripePaymentService {
    private string $key;
    private string $url;

    public function __construct() {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');
    }

    public function createPayment(): PaymentResponse {
        return new PaymentResponse('https://google.com');
    }
}
