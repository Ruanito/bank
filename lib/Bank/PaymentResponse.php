<?php

namespace Internal\Bank;

class PaymentResponse {

    private string $redirectUrl;

    public function __construct(string $redirectUrl) {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string {
        return $this->redirectUrl;
    }

}
