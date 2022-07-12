<?php

namespace Internal\Bank\Product;

interface BankProductResponseInterface {
    public function getProductId(): string;
    public function getName(): string;
    public function getDescription(): string;
    public function getCurrency(): string;
    public function getAmount(): int;
    public function getAttributes(): array;
    public function getExternalReference(): string;
}
