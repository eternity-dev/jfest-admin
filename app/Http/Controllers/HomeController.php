<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Competition;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $totalPaymentsAmount = Payment::whereNot('transaction_id', null)->where('status', 'success')->sum('amount');
        $totalPaymentsFee = Payment::whereNot('transaction_id', null)->sum('fee');
        $totalPaidTickets = Ticket::whereNot('code', null)->whereNot('price', '0')->count();
        $totalFreePassTickets = Ticket::whereNot('code', null)->where('price', '0')->count();
        $totalRegistrations = Registration::whereNot('uuid', null)->count();

        $paidPayments = json_encode(Payment::select([
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('MAX(amount) as max_amount'),
        ])
            ->whereNot('transaction_id', null)
            ->where(function (Builder $query) {
                $query->where('status', PaymentStatusEnum::Success->value);
                $query->orWhere('status', PaymentStatusEnum::Challenge->value);
            })
            ->whereDate('created_at', now())
            ->groupBy('hour')
            ->get());

        $sumOfPaidPayments = json_encode(Payment::select([
            DB::raw('DATE(updated_at) as date'),
            DB::raw('SUM(amount) as sum_of_amount'),
            DB::raw('SUM(fee) as sum_of_fee'),
        ])
            ->whereNot('transaction_id', null)
            ->where(function (Builder $query) {
                $query->where('status', PaymentStatusEnum::Success->value);
                $query->orWhere('status', PaymentStatusEnum::Challenge->value);
            })
            ->whereBetween('updated_at', [now()->subDays(5), now()->addDays(1)])
            ->groupBy('date')
            ->get());

        $paymentMethods = json_encode(Payment::select([
            DB::raw('COUNT(id) as total_used'),
            'method'
        ])
            ->whereNot('transaction_id', null)
            ->where(function (Builder $query) {
                $query->where('status', 'success');
                $query->orWhere('status', PaymentStatusEnum::Challenge->value);
            })
            ->groupBy('method')
            ->get());

        $registrationsCountByComp = json_encode(Competition::withCount('registrations')->get());
        $ticketsCountPerHour = json_encode(Ticket::select(
            DB::raw('HOUR(created_at) AS hour'),
            DB::raw('COUNT(id) AS total_tickets')
        )
            ->whereNot('code', null)
            ->whereNot('price', '0')
            ->whereDate('created_at', now())
            ->groupBy('hour')
            ->get());

        return view('home.index', [
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'json_paid_payments' => $paidPayments,
                'json_paid_tickets' => $ticketsCountPerHour,
                'json_sum_of_paid_payments' => $sumOfPaidPayments,
                'json_payment_methods' => $paymentMethods,
                'json_registrations_count_by_comp' => $registrationsCountByComp,
                'total_balance' => number_format($totalPaymentsAmount + $totalPaymentsFee, 2, ',', '.'),
                'total_paid_tickets' => $totalPaidTickets,
                'total_free_pass_tickets' => $totalFreePassTickets,
                'total_registrations' => $totalRegistrations
            ]),
            ...$this->withUser($request)
        ]);
    }
}
