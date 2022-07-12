<?php

namespace Internal\Bank\Product;

class BankProductRequest {
    private string $name;
    private string $description;
    private int $amount;

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getAmount(): int {
        return $this->amount;
    }

    public static function build(): BankProductRequestBuilder {
        $instance = new self();
        $property_setter = function($name, $value) use ($instance) {
            $instance->{$name} = $value;
        };

        return new BankProductRequestBuilder($instance, $property_setter);
    }
}
