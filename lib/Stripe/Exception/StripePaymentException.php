<?php

namespace Internal\Stripe\Exception;

use Internal\Bank\Payment\BankPaymentException;

class StripePaymentException extends BankPaymentException {}
