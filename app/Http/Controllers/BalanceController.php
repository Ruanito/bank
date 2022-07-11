<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Internal\Bank\Balance\BankBalanceService;

class BalanceController extends Controller {
    public function index(): JsonResponse {
        $balance = BankBalanceService::getBalance();
        return response()->json(['status' => 'success', 'data' => [
            'amount' => $balance->getAmount(),
            'currency' => $balance->getCurrency(),
        ]]);
    }
}
