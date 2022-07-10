<?php

namespace Internal\Stripe\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\PaymentResponse;
use Internal\Stripe\Exception\StripePaymentException;

class StripePaymentService {
    private string $key;
    private string $url;

    public function __construct() {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');
    }

    /**
     * @throws StripePaymentException
     */
    public function create(array $items): PaymentResponse {
        $data = [
          'line_items' => $items
        ];

        $payment = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/payment_links", $data);
        Log::info('stripe.payment_links', ['request' => $data,'response' => json_decode($payment->body())]);

        if ($payment->status() !== 200) {
            throw new StripePaymentException($payment->body());
        }

        return new PaymentResponse($payment['url']);
    }
}
