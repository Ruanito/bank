<?php

namespace Internal\Bank\Payment;

interface BankPaymentResponseInterface {
    public function getRedirectUrl(): string;
}
