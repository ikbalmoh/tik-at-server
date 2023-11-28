<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/operator', [AuthController::class, 'authOperator']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/transaction', [TransactionController::class, 'store']);

    Route::prefix('ticket')->group(function () {
        Route::get('/types', [TicketController::class, 'types']);
        Route::get('/{id}', [TicketController::class, 'show']);
    });
});

Route::post('/ticket/{id}', [TicketController::class, 'entrance']);
