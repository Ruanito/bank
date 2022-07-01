<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Internal\Stripe\Balance\StripeBalanceService;

class BalanceController extends Controller {
    public function index(): JsonResponse {
        $balance = new StripeBalanceService();
        return response()->json(['status' => 'success', 'data' => [
            'amount' => $balance->getBalance()->getAmount(),
            'currency' => $balance->getBalance()->getCurrency(),
        ]]);
    }
}
