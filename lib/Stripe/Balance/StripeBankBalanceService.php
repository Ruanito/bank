<?php

namespace Internal\Stripe\Balance;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\Balance\BankBalanceException;
use Internal\Bank\Balance\BankBalanceResponseInterface;
use Internal\Bank\Balance\BankBalanceServiceInterface;

class StripeBankBalanceService implements BankBalanceServiceInterface {
    private string $key;
    private string $url;

    public function __construct() {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');
    }

    public function isActive(): bool {
        return true;
    }

    /**
     * @throws BankBalanceException
     *
     * @return BankBalanceResponseInterface
     */
    public function getBalance(): BankBalanceResponseInterface {
        $balance = Http::withToken($this->key)->get("{$this->url}/balance");
        Log::debug($balance->body());

        if ($balance->status() === 200) {
            return StripeBalanceResponse::build()
                ->withAmount($balance['available'][0]['amount'])
                ->withCurrency($balance['available'][0]['currency'])
                ->build();
        }

        throw new BankBalanceException('StripBalanceServiceError');
    }
}
