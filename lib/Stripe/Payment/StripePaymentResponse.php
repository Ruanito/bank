<?php

namespace Internal\Stripe\Payment;

use Internal\Bank\Payment\BankPaymentResponseInterface;

class StripePaymentResponse implements BankPaymentResponseInterface {
    private string $url;

    public function getRedirectUrl(): string {
        return $this->url;
    }

    public static function builder(): StripePaymentResponseBuilder {
        $instance = new self();
        $property_setter = function ($name, $value) use ($instance) {
            $instance->{$name} = $value;
        };

        return new StripePaymentResponseBuilder($instance, $property_setter);
    }
}
