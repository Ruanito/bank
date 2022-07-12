<?php

namespace Internal\Stripe\Product;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\Product\BankProductRequest;
use Internal\Stripe\Exception\StripePriceException;
use Internal\Stripe\Exception\StripeProductException;

class StripeProductService {
    private string $key;
    private string $url;

    private BankProductRequest $bank_product_request;

    public function __construct(BankProductRequest $bank_product_request) {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');

        $this->bank_product_request = $bank_product_request;
    }

    /**
     * @throws StripeProductException
     * @throws StripePriceException
     */
    public function create(): StripeProductResponse {
        $product = $this->createProduct();
        $price = $this->createPrice($product);

         return StripeProductResponse::builder()
             ->withProductId($product['id'])
             ->withName($product['name'])
             ->withDescription($product['description'])
             ->withPriceId($price['id'])
             ->withCurrency($price['currency'])
             ->withAmount($price['unit_amount'])
             ->build();
    }

    /**
     * @throws StripeProductException
     */
    private function createProduct(): mixed {
        $data = [
            'name' => $this->bank_product_request->getName(),
            'description' => $this->bank_product_request->getDescription(),
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
    private function createPrice(mixed $product): mixed {
        $data = [
            'currency' => 'brl',
            'product' => $product['id'],
            'unit_amount' => $this->bank_product_request->getAmount(),
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
