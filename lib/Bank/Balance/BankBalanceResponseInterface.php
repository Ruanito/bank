<?php

namespace Internal\Bank\Balance;

interface BankBalanceResponseInterface {
    public function getAmount(): int;
    public function getCurrency(): string;
}
