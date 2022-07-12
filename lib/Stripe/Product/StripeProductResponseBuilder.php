<?php

namespace Internal\Stripe\Product;

class StripeProductResponseBuilder {
    /*
     * @var StripeProductResponse
     */
    private StripeProductResponse $instance;
    /*
     * @var callable
     */
    private $property_setter;

    public function __construct(StripeProductResponse $instance, callable $property_setter) {
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

    public function withProductId(string $product_id): self {
        return $this->propertySetter('product_id', $product_id);
    }

    public function withName(string $name): self {
        return $this->propertySetter('name', $name);
    }

    public function withDescription(string $description): self {
        return $this->propertySetter('description', $description);
    }

    public function withCurrency(string $currency): self {
        return $this->propertySetter('currency', $currency);
    }

    public function withAmount(int $amount): self {
        return $this->propertySetter('amount', $amount);
    }

    /**
     * @throws \Exception
     */
    public function withExternalReference(string $external_reference): self {
        return $this->propertySetter('external_reference', $external_reference);
    }

    public function build(): StripeProductResponse {
        unset($this->property_setter);
        return $this->instance;
    }
}
