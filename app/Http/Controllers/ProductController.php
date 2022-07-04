<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController {
    public function create(Request $request): JsonResponse {
        return response()
            ->json(['status' => 'success'], 201);
    }
}
