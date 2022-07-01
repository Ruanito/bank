<?php

namespace Internal\Stripe\Balance;

use Internal\Bank\BalanceResponse;

class StripeBalanceService {
    public function getBalance(): BalanceResponse {
        return new BalanceResponse(1000, 'usd');
    }
}
