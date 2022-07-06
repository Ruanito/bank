<?php

namespace Internal\Stripe\Product;

use Internal\Bank\ProductResponse;

class StripeProductResponse implements ProductResponse {

    private string $product_id;
    private string $name;
    private string $description;

    public function getProductId(): string {
        return $this->product_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getAttributes(): array {
        return [
          'product_id' => $this->product_id,
          'name' => $this->name,
          'description' => $this->description,
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
