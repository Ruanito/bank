<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class PaymentsController extends Controller {
    public function create(): JsonResponse {
        return response()->json(['status' => 'success']);
    }
}
