<?php

namespace Internal\Stripe\Balance;

class StripeBalanceResponseBuilder {
    private StripeBalanceResponse $instance;
    private $property_setter;

    public function __construct(StripeBalanceResponse $instance, callable $property_setter) {
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
    public function withAmount(int $amount): self {
        return $this->propertySetter('amount', $amount);
    }

    /**
     * @throws \Exception
     */
    public function withCurrency(string $currency): self {
        return $this->propertySetter('currency', $currency);
    }

    public function build(): StripeBalanceResponse {
        unset($this->property_setter);
        return $this->instance;
    }
}
