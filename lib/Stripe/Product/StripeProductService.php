<?php

namespace Internal\Stripe\Product;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Stripe\Exception\StripePriceException;
use Internal\Stripe\Exception\StripeProductException;

class StripeProductService {
    private string $key;
    private string $url;

    private string $name;
    private string $description;
    private int $amount;

    public function __construct(string $name, string $description, int $amount) {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');

        $this->name = $name;
        $this->description = $description;
        $this->amount = $amount;
    }

    /**
     * @throws StripeProductException
     * @throws StripePriceException
     */
    public function createProduct(): StripeProductResponse {
        $productData = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        $product = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/products", $productData);

        Log::debug($product->body());
        if ($product->status() !== 200) {
            throw new StripeProductException($product->body());
        }

        $priceData = [
            'currency' => 'brl',
            'product' => $product['id'],
            'unit_amount' => $this->amount,
        ];

        $price = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/prices", $priceData);


        Log::debug($price->body());
        if ($price->status() !== 200) {
            throw new StripePriceException($product->body());
        }

         return StripeProductResponse::builder()
             ->withProductId($product['id'])
             ->withName($product['name'])
             ->withDescription($product['description'])
             ->withPriceId($price['id'])
             ->withCurrency($price['currency'])
             ->withAmount($price['unit_amount'])
             ->build();
    }
}
