<?php

namespace Internal\Bank\Payment;

class BankPaymentRequest {
    private string $product;
    private int $quantity;

    public function getProduct(): string {
        return $this->product;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public static function builder(): BankPaymentRequestBuilder {
        $instance = new self();
        $property_setter = function($name, $value) use ($instance) {
            $instance->{$name} = $value;
        };

        return new BankPaymentRequestBuilder($instance, $property_setter);
    }
}
