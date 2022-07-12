<?php

namespace Internal\Bank\Payment;

use Illuminate\Support\Facades\Log;
use Internal\Stripe\Payment\StripePaymentService;

class BankPaymentService {
    /**
     * @throws BankPaymentException
     * @throws \Exception
     */
    public static function create(BankPaymentRequest ...$bank_payment_request) {
        $providers = self::getProviders();
        foreach ($providers as $provider) {
            if ($provider->isActive()) {
                try {
                    return $provider->create(...$bank_payment_request);
                } catch (BankPaymentException $e) {
                    Log::error('bank.product.service', ['error' => json_decode($e->getMessage())]);
                }
            }
        }

        throw new BankPaymentException('Could not create a PaymentLink');
    }

    private static function getProviders(): array {
        return [
            new StripePaymentService(),
        ];
    }
}
