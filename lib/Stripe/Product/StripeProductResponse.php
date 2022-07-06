<?php

namespace Internal\Stripe\Product;

use Internal\Bank\ProductResponse;

class StripeProductResponse {

    private string $product_id;
    private string $name;
    private string $description;
    private string $price_id;
    private string $currency;
    private int $amount;

    public function getProductId(): string {
        return $this->product_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPriceId(): string {
        return $this->price_id;
    }

    public function getAttributes(): array {
        return [
          'product_id' => $this->product_id,
          'name' => $this->name,
          'description' => $this->description,
          'price_id' => $this->price_id,
          'currency' => $this->currency,
          'amount' => $this->amount,
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
