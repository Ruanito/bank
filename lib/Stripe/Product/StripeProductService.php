<?php

namespace Internal\Stripe\Product;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\Product\BankProductRequest;
use Internal\Bank\Product\BankProductResponseInterface;
use Internal\Bank\Product\BankProductServiceInterface;
use Internal\Stripe\Exception\StripePriceException;
use Internal\Stripe\Exception\StripeProductException;

class StripeProductService implements BankProductServiceInterface {
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
     * @throws StripeProductException
     * @throws StripePriceException
     * @throws \Exception
     */
    public function create(BankProductRequest $bank_product_request): BankProductResponseInterface {
        $product = $this->createProduct($bank_product_request);
        $price = $this->createPrice($bank_product_request, $product);

         return StripeProductResponse::builder()
             ->withProductId($product['id'])
             ->withName($product['name'])
             ->withDescription($product['description'])
             ->withExternalReference($price['id'])
             ->withCurrency($price['currency'])
             ->withAmount($price['unit_amount'])
             ->build();
    }

    /**
     * @throws StripeProductException
     */
    private function createProduct(BankProductRequest $bank_product_request): mixed {
        $data = [
            'name' => $bank_product_request->getName(),
            'description' => $bank_product_request->getDescription(),
        ];

        $product = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/products", $data);

        Log::info('stripe.product', ['request' => $data, 'response' => json_decode($product->body(), true)]);
        if ($product->status() !== 200) {
            throw new StripeProductException($product->body());
        }

        return $product;
    }

    /**
     * @throws StripePriceException
     */
    private function createPrice(
        BankProductRequest $bank_product_request,
        mixed $product
    ): mixed {
        $data = [
            'currency' => 'brl',
            'product' => $product['id'],
            'unit_amount' => $bank_product_request->getAmount(),
        ];

        $price = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/prices", $data);

        Log::info('stripe.price', ['request' => $data, 'response' => json_decode($price->body(), true)]);
        if ($price->status() !== 200) {
            throw new StripePriceException($product->body());
        }

        return $price;
    }
}
