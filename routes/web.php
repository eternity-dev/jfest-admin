<?php

use App\Models\Activity;
use App\Models\ActivitySale;
use App\Models\Competition;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Ticket;
use App\Models\User;
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

Route::get('/', function () {
    dump(Activity::all());
    dump(ActivitySale::all());
    dump(Competition::all());
    dump(Order::all());
    dump(Payment::all());
    dump(Registration::all());
    dump(Team::all());
    dump(TeamMember::all());
    dump(Ticket::all());
    dump(User::all());
});
