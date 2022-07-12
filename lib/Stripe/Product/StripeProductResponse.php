<?php

namespace Internal\Stripe\Product;

use Internal\Bank\Product\BankProductResponseInterface;

class StripeProductResponse implements BankProductResponseInterface {

    private string $product_id;
    private string $name;
    private string $description;
    private string $currency;
    private int $amount;
    private string $external_reference;

    public function getProductId(): string {
        return $this->product_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getCurrency(): string {
        return $this->currency;
    }

    public function getAmount(): int {
        return $this->amount;
    }

    public function getExternalReference(): string {
        return $this->external_reference;
    }

    public function getAttributes(): array {
        return [
            'product_id' => $this->product_id,
            'name' => $this->name,
            'description' => $this->description,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'external_reference' => $this->external_reference
        ];
    }

    public static function builder(): StripeProductResponseBuilder {
        $instance = new self();
        $property_setter = function ($name, $value) use ($instance) {
            $instance->{$name} = $value;
        };

        return new StripeProductResponseBuilder($instance, $property_setter);
    }
}
