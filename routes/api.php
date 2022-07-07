<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([\App\Http\Middleware\LogRequest::class])->group(function () {
    Route::get('balance', [BalanceController::class, 'index']);

    Route::post('payments', [PaymentsController::class, 'create']);

    Route::post('products', [ProductController::class, 'create']);
});
