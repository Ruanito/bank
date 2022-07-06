<?php

namespace Internal\Stripe\Product;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\ProductResponse;
use Internal\Stripe\Exception\StripePriceException;
use Internal\Stripe\Exception\StripeProductException;

class StripeProductService {
    private string $key;
    private string $url;

    public function __construct() {
        $this->key = env('STRIPE_PRIVATE_KEY');
        $this->url = env('STRIPE_URL');
    }

    /**
     * @throws StripeProductException
     * @throws StripePriceException
     */
    public function createProduct(): ProductResponse {
        $productData = [
            'name' => 'FC Barcelona',
            'description' => 'Camiseta de Jogo',
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
            'unit_amount' => 10000,
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
             ->build();
    }
}
