<?php

namespace Internal\Bank;

interface ProductResponse {
    public function getId(): string;
    public function getName(): string;
    public function getDescription(): string;
    public function getAttributes(): array;
}
