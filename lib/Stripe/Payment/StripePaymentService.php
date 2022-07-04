<?php

namespace Internal\Stripe\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\PaymentResponse;

class StripePaymentService {
    private string $key;
    private string $url;

    public function __construct() {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');
    }

    public function createPayment(): PaymentResponse {
        $data = [
          'line_items' => [
              [
                  'price' => 'price_1LHqv3Frn2rP77Aaxij7DT9s',
                  'quantity' => 1,
              ]
          ]
        ];

        $payment = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/payment_links", $data);

        Log::warning($payment->body());
        return new PaymentResponse($payment['url']);
    }
}
