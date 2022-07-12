<?php

namespace Internal\Bank\Payment;

interface BankPaymentServiceInterface {
    public function isActive(): bool;
    public function create(BankPaymentRequest ...$bank_payment_request): BankPaymentResponseInterface;
}
