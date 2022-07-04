<?php

namespace Internal\Stripe\Product;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Internal\Bank\ProductResponse;
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
     */
    public function createProduct(): ProductResponse {
        $data = [
            'name' => 'FC Barcelona',
            'description' => 'Camiseta de Jogo',
        ];

        $product = Http::withToken($this->key)
            ->asForm()
            ->post("{$this->url}/products", $data);

        Log::debug($product->body());
        if ($product->status() === 200) {
            return new StripeProductResponse($product['id'], $product['name'], $product['description']);
        }

        throw new StripeProductException($product->body());
    }
}
