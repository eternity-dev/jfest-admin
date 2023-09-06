<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::name('auth.')->prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/attempt', [LoginController::class, 'index'])->name('attempt.index');
        Route::post('/attempt', [LoginController::class, 'store'])->name('attempt.store');
    });

    Route::get('/revoke', LogoutController::class)->middleware('auth')->name('revoke');
});

Route::name('dashboard.')->prefix('d')->middleware('auth')->group(function () {
    Route::name('home.')->prefix('home')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('index');
    });

    Route::name('orders.')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
    });
});
