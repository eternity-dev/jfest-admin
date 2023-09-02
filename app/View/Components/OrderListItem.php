<?php

namespace App\View\Components;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OrderListItem extends Component
{
    public array $meta;

    public array $url;

    public OrderStatusEnum $status;

    public string $statusClass;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Order $order
    ) {
        $this->meta = [
            'date_of_created' => !is_null($order->created_at) ? $order->created_at->diffForHumans() : '-',
            'date_of_updated' => !is_null($order->updated_at) ? $order->updated_at->diffForHumans() : '-',
            'formatted_order_amount' => number_format($order->total_price, 2, ',', '.'),
            'formatted_payment_amount' => number_format($order->payment->amount ?? 0, 2, ',', '.'),
            'formatted_payment_fee' => number_format($order->payment->fee ?? 0, 2, ',', '.'),
        ];

        $this->url = [
            'update_url' => route('organizer.order.edit', ['order' => $order]),
            'remove_url' => '/',
            'mark_as_paid_url' => route('organizer.order.edit', [
                'order' => $order,
                'state' => 'mark-as-paid'
            ])
        ];

        $this->status = $order->status;
        $this->statusClass = ($this->status == OrderStatusEnum::Paid
            ? 'success'
            : ($this->status == OrderStatusEnum::Pending
                ? 'info'
                : 'danger'));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.order-list-item');
    }
}
