<?php

namespace App\View\Components;

use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketListItem extends Component
{
    public array $meta;

    public array $url;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Ticket $ticket
    ) {
        $this->meta = [
            'date_of_attended' => !is_null($ticket->attended_at) ? $ticket->attended_at->diffForHumans() : '-',
            'date_of_created' => !is_null($ticket->created_at) ? $ticket->created_at->diffForHumans() : '-',
            'date_of_updated' => !is_null($ticket->updated_at) ? $ticket->updated_at->diffForHumans() : '-',
            'formatted_order_amount' => number_format($ticket->order->total_price, 2, ',', '.'),
            'formatted_payment_amount' => number_format($ticket->order->payment->amount, 2, ',', '.'),
            'formatted_payment_fee' => number_format($ticket->order->payment->fee, 2, ',', '.'),
        ];

        $this->url = [
            'update_url' => '/',
            'remove_url' => '/',
            'mark_attended_url' => route('organizer.ticket.edit', [
                'ticket' => $ticket,
                'state' => 'mark-as-attended'
            ])
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ticket-list-item');
    }
}
