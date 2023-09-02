<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with([
            'user',
            'payment',
            'tickets',
            'registrations'
        ]);

        if (!is_null($query = $request->query('search', null))) {
            $orders
                ->where('reference', 'LIKE', '%' . $query . '%')
                ->orWhereRelation('user', 'name', 'LIKE', '%' . $query . '%')
                ->orWhereRelation('user', 'email', 'LIKE', '%' . $query . '%');
        }

        return view('orders.index', [
            'data' => [
                'orders' => $orders->paginate(25)
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'search_query' => $request->query('search', null)
            ]),
            ...$this->withUser($request)
        ]);
    }

    public function edit(Request $request, Order $order)
    {
        if ($query = $request->query('state', false)) {
            if ($query != 'mark-as-paid') return redirect()->back();

            $order->status = OrderStatusEnum::Paid;
            $order->save();

            return redirect()->back();
        }
    }

    public function update()
    {

    }
}
