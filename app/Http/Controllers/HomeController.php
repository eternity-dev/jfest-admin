<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $usersChartData = User::select(
                DB::raw('COUNT(id) AS total_user'),
                DB::raw('DATE(created_at) AS date_of_created_at')
            )->groupBy('date_of_created_at')->get();

        $ticketsSoldChartData = Ticket::whereNotFreePass()
            ->select(
                DB::raw('COUNT(id) AS total_sold'),
                DB::raw('DATE(created_at) AS date_of_created_at'),
                DB::raw('HOUR(created_at) AS hour_of_created_at'),
                DB::raw('MINUTE(created_at) AS min_of_created_at'),
            )->groupBy('date_of_created_at', 'hour_of_created_at', 'min_of_created_at')->get();

        $totalRevenueChartData = Payment::whereSuccess()
            ->select(
                DB::raw('SUM(amount) AS total_paid_amount'),
                DB::raw('SUM(fee) AS total_paid_fee'),
                DB::raw('DATE(created_at) AS date_of_created_at'),
                DB::raw('HOUR(created_at) AS hour_of_created_at'),
                DB::raw('MINUTE(created_at) AS min_of_created_at'),
            )->groupBy('date_of_created_at', 'hour_of_created_at', 'min_of_created_at')->get();

        $paymentMethodsChartData = Payment::whereSuccess()
            ->select(
                DB::raw('COUNT(id) AS total_used'),
                'method'
            )->groupBy('method')->get();

        $registrationsSoldChartData = Registration::with('competition:id,name')->whereIsRegistered()
            ->select(
                DB::raw('COUNT(id) AS total_registered'),
                'competition_id'
            )->groupBy('competition_id')->get();

        return view('home.index', [
            'data' => [
                'chart' => [
                    'users' => $usersChartData,
                    'tickets_sold' => $ticketsSoldChartData,
                    'total_revenue' => $totalRevenueChartData,
                    'payments_method' => $paymentMethodsChartData,
                    'registrations_registered' => $registrationsSoldChartData
                ]
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'total_revenue' => $this->calculateTotalRevenue(),
                'total_tickets_sold' => $this->calculateTotalTicketsSold(),
                'total_registrations_sold' => $this->calculateTotalRegistrationsSold()
            ]),
            ...$this->withUser($request)
        ]);
    }

    private function calculateTotalRegistrationsSold()
    {
        $registrations = Registration::whereIsRegistered();

        $allRegistrations = $registrations->select(DB::raw('COUNT(id) AS total_sold'))->first();
        $allYesterdayRegistrations = $registrations
            ->select(DB::raw('COUNT(id) AS total_sold'))
            ->whereDate('created_at', '<', now()->subDay())
            ->first();

        $totalRegistrations = $allRegistrations->total_sold;
        $diffOfTotalRegistrations = $totalRegistrations - $allYesterdayRegistrations->total_sold;

        return [
            'all' => $totalRegistrations,
            'diff' => $diffOfTotalRegistrations
        ];
    }

    private function calculateTotalRevenue()
    {
        $payments = Payment::whereSuccess();

        $allPayments = $payments->select(
            DB::raw('SUM(amount) AS total_amount'),
            DB::raw('SUM(fee) AS total_fee'),
        )->first();

        $allYesterdayPayments = $payments
            ->whereDate('created_at', '<', now()->subDay())
            ->select(
                DB::raw('SUM(amount) AS total_amount'),
                DB::raw('SUM(fee) AS total_fee'),
            )->first();

        $totalPayments = $allPayments->total_amount + $allPayments->total_fee;
        $diffOfTotalPayments = $totalPayments - ($allYesterdayPayments->total_amount + $allYesterdayPayments->total_fee);

        return [
            'all' => $totalPayments,
            'diff' => $diffOfTotalPayments
        ];
    }

    private function calculateTotalTicketsSold()
    {
        $tickets = Ticket::whereNotFreePass();

        $allTickets = $tickets->select(DB::raw('COUNT(id) AS total_sold'))->first();
        $allYesterdayTickets = $tickets
            ->select(DB::raw('COUNT(id) AS total_sold'))
            ->whereDate('created_at', '<', now()->subDay())
            ->first();

        $totalTickets = $allTickets->total_sold;
        $diffOfTotalTickets = $totalTickets - $allYesterdayTickets->total_sold;

        return [
            'all' => $totalTickets,
            'diff' => $diffOfTotalTickets
        ];
    }
}
