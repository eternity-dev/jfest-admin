<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Http\Requests\Ticket\StoreRequest;
use App\Models\Activity;
use App\Models\ActivitySale;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function create(Request $request)
    {
        $activities = Activity::all();
        $references = Order::with('user')->get();
        $sales = ActivitySale::select('id', 'name')->get();
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

        $ticketsCount = Ticket::count();
        $ticketActivity = Activity::where('name', 'like', $data['activity'])->first();
        $ticketPrice = ActivitySale::find($data['price']);
        $tickets = collect([]);

        for ($i = 0; $i < $data['amount']; $i++) {
            $ticketCode = sprintf(
                '%s-%s-%s-%s',
                'JFEST-7',
                str($ticketPrice->unique_id)->upper(),
                str(explode('-', $data['user'])[0])->upper(),
                str($ticketsCount + $i)->padLeft(7, '0')
            );

            $tickets->push(new Ticket([
                'uuid' =>  $data['uuid'],
                'code' =>  $ticketCode,
                'activity_id' => $ticketActivity->id,
                'user_id' => $data['user'],
                'price' => $ticketPrice->price
            ]));
        }

        $order = new Order(['user_id' => $data['user'], 'total_price' => $ticketPrice->price * $data['amount']]);
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

        $request->session()->flash(
            'message',
            'New ticket has been created'
        );

        return to_route('dashboard.orders.show', [
            'order' => $order
        ]);
    }
}
