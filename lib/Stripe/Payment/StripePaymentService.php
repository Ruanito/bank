<?php

namespace Internal\Stripe\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\Payment\BankPaymentRequest;
use Internal\Bank\Payment\BankPaymentResponseInterface;
use Internal\Bank\Payment\BankPaymentServiceInterface;
use Internal\Stripe\Exception\StripePaymentException;

class StripePaymentService implements BankPaymentServiceInterface {
    private string $key;
    private string $url;

    public function __construct() {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');
    }

    public function isActive(): bool {
        return true;
    }

    /**
     * @throws StripePaymentException
     * @throws \Exception
     */
    public function create(BankPaymentRequest ...$bank_payment_request): BankPaymentResponseInterface {
        $data = [
          'line_items' => $this->getLineItems(...$bank_payment_request),
        ];

        $payment = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/payment_links", $data);
        Log::info('stripe.payment_links', ['request' => $data,'response' => json_decode($payment->body())]);

        if ($payment->status() !== 200) {
            throw new StripePaymentException($payment->body());
        }

        return StripePaymentResponse::builder()
            ->withUrl($payment['url'])
            ->build();
    }

    private function getLineItems(BankPaymentRequest ...$bank_payment_request): array {
        $items = [];
        foreach ($bank_payment_request as $request) {
            $items[] = ['price' => $request->getProduct(), 'quantity' => $request->getQuantity()];
        }
        return $items;
    }
}
