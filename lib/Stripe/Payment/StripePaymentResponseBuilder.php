<?php

namespace Internal\Stripe\Payment;

class StripePaymentResponseBuilder {

    private StripePaymentResponse $instance;
    private $property_setter;

    public function __construct(StripePaymentResponse $instance, callable $property_setter) {
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
    public function withUrl(string $url): self {
        return $this->propertySetter('url', $url);
    }

    public function build(): StripePaymentResponse {
        unset($this->property_setter);
        return $this->instance;
    }
}
