<?php

namespace Internal\Bank\Product;

interface BankProductServiceInterface {
    public function isActive(): bool;
    public function create(BankProductRequest $bank_product_request): BankProductResponseInterface;
}
