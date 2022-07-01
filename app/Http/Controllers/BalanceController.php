<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BalanceController extends Controller {
    public function index(): JsonResponse {
        return response()->json(['status' => 'success']);
    }
}
