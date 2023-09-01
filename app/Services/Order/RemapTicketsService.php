<?php

namespace App\Services\Order;

use App\Models\Ticket;
use App\Services\Contract\RemapOrderItems;
use Illuminate\Support\Collection;

class RemapTicketsService implements RemapOrderItems
{
    public function handle(Collection|null &$items): void
    {
        if (is_null($items)) {
            return;
        }

        $items = $items->map(function (Ticket $ticket) {
            $date = $ticket->activity->date;
            $ticket->activity->dateStr = $date->diffForHumans();
            $ticket->remove_url = route('user.order.activity.remove', [
                'ticket' => $ticket
            ]);

            return $ticket;
        });
    }
}
