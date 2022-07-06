<?php

namespace Internal\Bank;

interface ProductResponse {
    public function getProductId(): string;
    public function getName(): string;
    public function getDescription(): string;
    public function getAttributes(): array;
}
