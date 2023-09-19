<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Http\Requests\Ticket\StoreRequest;
use App\Mail\Payment\SuccessPayment;
use App\Models\Activity;
use App\Models\ActivitySale;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function create(Request $request)
    {
        $activities = Activity::all();
        $references = Order::with('user')->get();
        $sales = ActivitySale::select('id', 'name', 'price')->get();
        $users = User::select('uuid', 'email')->get();

        if (!is_null($query = $request->query('user', null))) {
            $user = User::select('uuid', 'email')->where('id', $query)->first();
        }

        return view('tickets.create', [
            'data' => [
                'activities' => $activities,
                'references' => $references,
                'sales' => $sales,
                'users' => $users,
                'user' => $user ?? null
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'order' => $references->firstWhere('id', $request->query('order', null))->reference ?? null,
                'user' => $request->query('user', null),
            ]),
            ...$this->withUser($request)
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $user = User::select('uuid')->where('email', $data['user'])->first();
        $ticketsCount = Ticket::count();
        $ticketActivity = Activity::where('name', 'like', $data['activity'])->first();
        $ticketPrice = ActivitySale::find($data['price']);
        $tickets = collect([]);

        for ($i = 0; $i < $data['amount']; $i++) {
            $ticketUuid = Str::uuid();
            $ticketCode = sprintf(
                '%s-%s-%s-%s',
                'JFEST-7',
                str($ticketPrice->unique_id)->upper(),
                str(explode('-', $user->uuid)[0])->upper(),
                str($ticketsCount + $i)->padLeft(7, '0')
            );

            $tickets->push(new Ticket([
                'uuid' =>  $ticketUuid,
                'code' =>  $ticketCode,
                'activity_id' => $ticketActivity->id,
                'user_id' => $user->uuid,
                'price' => $ticketPrice->price
            ]));
        }

        $order = new Order([
            'user_id' => $user->uuid,
            'total_price' => $ticketPrice->price * $data['amount'],
            'status' => OrderStatusEnum::Paid
        ]);

        $payment = new Payment([
            'transaction_id' => Str::uuid(),
            'amount' => $order->total_price,
            'fee' => 0,
            'link' => '',
            'method' => 'ots',
            'status' => PaymentStatusEnum::Success
        ]);

        $order->save();
        $order->payments()->save($payment);
        $order->tickets()->saveMany($tickets);
        $order->payment = $payment;

        $request->session()->flash(
            'message',
            'New ticket has been created'
        );

        Mail::to($order->user->email)->send(new SuccessPayment($order));

        return to_route('dashboard.orders.show', [
            'order' => $order
        ]);
    }
}
