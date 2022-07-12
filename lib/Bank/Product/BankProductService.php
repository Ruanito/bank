<?php

namespace Internal\Bank\Product;

use Illuminate\Support\Facades\Log;
use Internal\Stripe\Product\StripeProductService;

class BankProductService {
    /**
     * @throws \Exception
     */
    public static function create(BankProductRequest $bank_product_request): BankProductResponseInterface {
        $providers = self::getProviders();
        foreach ($providers as $provider) {
            if ($provider->isActive()) {
                try {
                    return $provider->create($bank_product_request);
                } catch (BankProductException $e) {
                    Log::error('bank.product.service', ['error' => $e->getMessage()]);
                }
            }
        }

        throw new BankProductException('Could not create a Product');
    }

    private static function getProviders(): array {
        return [
            new StripeProductService(),
        ];
    }
}
