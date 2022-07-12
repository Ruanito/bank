<?php

namespace Internal\Bank\Product;

class BankProductRequestBuilder {
    private BankProductRequest $instance;
    private $property_setter;

    public function __construct(BankProductRequest $instance, callable $property_setter) {
        $this->instance = $instance;
        $this->property_setter = $property_setter;
    }

    /**
     * @throws \Exception
     */
    private function propertySetter(string $name, mixed $value): self {
        if (!isset($this->property_setter)) {
            throw new \Exception('Cannot mutate the instance after build');
        }

        $property_setter = $this->property_setter;
        $property_setter($name, $value);
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function withName(string $name): self {
        return $this->propertySetter('name', $name);
    }

    public function withDescription(string $description): self {
        return $this->propertySetter('description', $description);
    }

    public function withAmount(int $amount): self {
        return $this->propertySetter('amount', $amount);
    }

    public function builder(): BankProductRequest {
        unset($this->property_setter);
        return $this->instance;
    }
}
