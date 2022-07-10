<?php

namespace Internal\Stripe\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\PaymentResponse;
use Internal\Stripe\Exception\StripePaymentException;

class StripePaymentService {
    /**
     * @throws StripePaymentException
     */
    public static function createPayment(array $items): PaymentResponse {
        $key = env('STRIPE_PRIVATE_KEY');
        $url = env('STRIPE_URL');

        $data = [
          'line_items' => $items
        ];

        $payment = Http::withToken($key)
            ->asForm()
            ->post("{$url}/payment_links", $data);
        Log::info('stripe.payment_links', ['request' => $data,'response' => json_decode($payment->body())]);

        if ($payment->status() !== 200) {
            throw new StripePaymentException($payment->body());
        }

        return new PaymentResponse($payment['url']);
    }
}
