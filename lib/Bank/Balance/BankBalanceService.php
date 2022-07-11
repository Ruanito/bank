<?php

namespace Internal\Bank\Balance;

use Illuminate\Support\Facades\Log;
use Internal\Stripe\Balance\StripeBankBalanceService;

class BankBalanceService {
    /**
     * @throws BankBalanceException
     */
    public static function getBalance(): BankBalanceResponseInterface {
       $providers = self::getProviders();

       foreach ($providers as $provider) {
           if ($provider->isActive()) {
               try {
                   return $provider->getBalance();
               } catch (BankBalanceException $e) {
                   Log::error('bank.balance.service', ['error' => $e->getMessage()]);
               }
           }
       }

        throw new BankBalanceException('not found provider for balance');
    }

    private static function getProviders(): array {
        return [
            new StripeBankBalanceService(),
        ];
    }
}
