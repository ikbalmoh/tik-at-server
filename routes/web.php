<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/report/transaction', [ReportController::class, 'transaction'])->name('report.transaction');
Route::get('/report/daily', [ReportController::class, 'daily'])->name('report.daily');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('setting')->group(function () {
        Route::get('/ticket', [SettingController::class, 'ticket'])->name('ticket.index');
        Route::post('/ticket', [SettingController::class, 'storeTicket'])->name('ticket.store');
        Route::put('/ticket/{id}', [SettingController::class, 'updateTicket'])->name('ticket.update');
        Route::delete('/ticket/{id}', [SettingController::class, 'destroyTicket'])->name('ticket.destroy');

        Route::get('/operators', [SettingController::class, 'operators'])->name('operator.index');
    });
});

require __DIR__ . '/auth.php';
