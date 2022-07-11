<?php

namespace Internal\Stripe\Balance;

use Internal\Bank\Balance\BankBalanceResponseInterface;
use Internal\Stripe\Payment\StripePaymentResponseBuilder;

class StripeBalanceResponse implements BankBalanceResponseInterface {
    private int $amount;
    private string $currency;

    public function getAmount(): int {
        return $this->amount;
    }

    public function getCurrency(): string {
        return $this->currency;
    }

    public static function build(): StripeBalanceResponseBuilder {
        $instance = new self();
        $property_setter = function ($name, $value) use ($instance) {
            $instance->{$name} = $value;
        };

        return new StripeBalanceResponseBuilder($instance, $property_setter);
    }
}
