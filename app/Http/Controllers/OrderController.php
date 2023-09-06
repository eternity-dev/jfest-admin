<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (!is_null($query = $request->query('search', null))) {
            $orders = Order::where([
                ['reference', 'like', '%' . $query . '%'],
            ])
                ->orWhereRelation('user', 'email', 'like', '%' . $query . '%')
                ->orWhereRelation('user', 'name', 'like', '%' . $query . '%')
                ->paginate(25);
        } else if (!is_null($queryByStatus = $request->query('status', null))) {
            $orders = Order::where('status', $queryByStatus)->paginate(25);
        } else { $orders = Order::paginate(25); }

        return view('orders.index', [
            'data' => [
                'orders' => $orders
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'search_query' => $query,
                'total_orders' => Order::count(),
                'total_paid_orders' => Order::where('status', OrderStatusEnum::Paid)->count(),
                'total_pending_orders' => Order::where('status', OrderStatusEnum::Pending)->count()
            ]),
            ...$this->withUser($request)
        ]);
    }

    public function show(Request $request, Order $order)
    {
        return view('orders.show', [
            'data' => [
                'order' => $order
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([]),
            ...$this->withUser($request)
        ]);
    }

    public function update(Request $request, Order $order)
    {
        if (!is_null($query = $request->query('state', null))) {
            switch ($query) {
                case 'mark-as-paid':
                    $order->status = OrderStatusEnum::Paid;
                    $order->save();

                    $request->session()->flash('message', 'Order has been updated');
                    break;
            }

            return redirect()->back();
        }

        $data = $request->all();

        foreach ($data['order'] as $key => $updatedOrder) {
            $order->{$key} = $updatedOrder;
        }

        if ($order->isDirty()) {
            $order->save();
            $request->session()->flash('message', 'Order has been updated');
        }

        return redirect()->back();
    }
}
