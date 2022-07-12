<?php

namespace Internal\Bank\Payment;

class BankPaymentRequestBuilder {
    private BankPaymentRequest $instance;
    private $property_setter;

    public function __construct(BankPaymentRequest $instance, callable $property_setter) {
        $this->instance = $instance;
        $this->property_setter = $property_setter;
    }

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
    public function withProduct(string $product): self {
        return $this->propertySetter('product', $product);
    }

    /**
     * @throws \Exception
     */
    public function withQuantity(int $quantity): self {
        return $this->propertySetter('quantity', $quantity);
    }

    public function build(): BankPaymentRequest {
        unset($this->property_setter);
        return $this->instance;
    }
}
