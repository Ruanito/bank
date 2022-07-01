<?php

namespace Internal\Bank;

class BalanceResponse {
    private int $amount;
    private string $currency;

    public function __construct(
        int $amount,
        string $currency
    ) {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getAmount(): int {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string {
        return $this->currency;
    }
}
