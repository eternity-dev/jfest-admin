<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
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

    Route::name('exports.')->prefix('exports')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::post('/registrations', [ExportController::class, 'storeRegistrations'])->name('store.registrations');
    });

    Route::name('orders.')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
    });

    Route::name('registrations.')->prefix('registrations')->group(function () {
        Route::get('/', [RegistrationController::class, 'index'])->name('index');
    });

    Route::name('tickets.')->prefix('tickets')->group(function () {
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
    });

    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
    });
});
