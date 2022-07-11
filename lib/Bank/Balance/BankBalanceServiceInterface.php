<?php

namespace Internal\Bank\Balance;

interface BankBalanceServiceInterface {
    public function isActive(): bool;
    public function getBalance(): BankBalanceResponseInterface;
}
