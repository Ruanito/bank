<?php

namespace Internal\Stripe\Product;

use Internal\Bank\ProductResponse;

class StripeProductResponse implements ProductResponse {

    private string $id;
    private string $name;
    private string $description;

    public function __construct(string $id, string $name, string $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getAttributes(): array {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'description' => $this->description,
        ];
    }
}
