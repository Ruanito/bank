<?php

namespace Internal\Stripe\Balance;

use Illuminate\Support\Facades\Http;
use Internal\Bank\BalanceResponse;
use Internal\Stripe\Exception\BalanceException;

class StripeBalanceService {
    private string $key;
    private string $url;

    public function __construct() {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');
    }
    /**
     * @throws \Exception
     *
     * @return BalanceResponse
     */
    public function getBalance(): BalanceResponse {
        $balance = Http::withToken($this->key)->get("{$this->url}/balance");

        if ($balance) {
            return new BalanceResponse($balance['available'][0]['amount'], $balance['available'][0]['currency']);
        }

        throw new BalanceException('StripBalanceServiceError');
    }
}
