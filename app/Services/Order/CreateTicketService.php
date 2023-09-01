<?php

namespace App\Services\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Activity;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Collection;

class CreateTicketService
{
    public function handle(User $user, Activity $activity, int $amount)
    {
        try {
            if ($user->orders->isEmpty()) {
                $order = new Order([
                    'total_price' => $activity->sale->price * $amount
                ]);

                $user->orders()->save($order);
                $order->tickets()->saveMany(
                    $this->generateTickets($user, $activity, $amount)
                );
            } else {
                $order = $user->orders()->where([
                    ['status', OrderStatusEnum::Pending->value],
                    ['expired_at', '>', now()]
                ])->latest()->first();

                if (is_null($order)) {
                    $order = new Order([
                        'total_price' => $activity->sale->price * $amount
                    ]);

                    $user->orders()->save($order);
                    $order->tickets()->saveMany(
                        $this->generateTickets($user, $activity, $amount)
                    );
                } else {
                    $order->total_price += ($activity->sale->price * $amount);

                    $order->save();
                    $order->tickets()->saveMany(
                        $this->generateTickets($user, $activity, $amount)
                    );
                }
            }

            logger()->channel('stack')->info('New order has been placed', [
                'user_id' => $user->uuid,
                'order_id' => $order->reference
            ]);

            return true;
        } catch (\Throwable $exception) {
            logger()->channel('error')->error($exception->getMessage(), [
                'user_id' => $user->uuid,
                'order_id' => $order->reference
            ]);

            return false;
        }
    }

    private function generateTickets(User $user, Activity $activity, int $amount)
    {
        $tickets = new Collection([]);

        for ($i = 0; $i < $amount; $i++) {
            $tickets->push(new Ticket([
                'activity_id' => $activity->id,
                'user_id' => $user->uuid,
                'price' => $activity->sale->price
            ]));
        }

        return $tickets;
    }
}
