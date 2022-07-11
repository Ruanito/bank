<?php

namespace Internal\Bank;

interface PaymentResponse {
    public function getRedirectUrl(): string;
}
