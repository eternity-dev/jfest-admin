<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\StoreRequest;
use App\Models\Activity;
use App\Models\ActivitySale;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create(Request $request)
    {
        $activity = Activity::where('slug', 'japanese-festival-7')->first();
        $references = Order::select('id', 'reference')->get();
        $sales = ActivitySale::select('id', 'name')->get();
        $users = User::select('uuid', 'email')->get();

        if (!is_null($query = $request->query('user', null))) {
            $user = User::select('uuid', 'email')->where('id', $query)->first();
        }

        return view('tickets.create', [
            'data' => [
                'activity' => $activity,
                'references' => $references,
                'sales' => $sales,
                'users' => $users,
                'user' => $user ?? null
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'order' => $request->query('order', null),
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
        $ticketCode = sprintf(
            '%s-%s-%s-%s',
            'JFEST-7',
            str($ticketPrice->unique_id)->upper(),
            str(explode('-', $data['user'])[0])->upper(),
            str($ticketsCount)->padLeft(7, '0')
        );

        $ticket = new Ticket([
            'uuid' => $data['uuid'],
            'code' => $ticketCode,
            'activity_id' => $ticketActivity->id,
            'order_id' => $data['reference'],
            'user_id' => $data['user'],
            'price' => $ticketPrice->price
        ]);

        $ticket->save();
        $request->session()->flash('message', 'New ticket has been created');

        return to_route('dashboard.home.index');
    }
}
