<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\TicketController;
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

Route::name('organizer.')->prefix('org')->middleware('auth')->group(function () {
    Route::get('/', function () {})->name('home');

    Route::name('ticket.')->prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
    });
});
