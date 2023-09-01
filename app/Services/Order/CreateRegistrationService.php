<?php

namespace App\Services\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Activity;
use App\Models\Competition;
use App\Models\Order;
use App\Models\Registration;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Ticket;
use App\Models\User;

class CreateRegistrationService
{
    public const INDIVIDUAL_COUNT = 1;
    public const TICKET_SLUG = 'japanese-festival-7';
    public const PRICE_OF_FREE_PASS = 0;

    public function handle(User $user, Competition $competition, array $data)
    {
        try {
            if ($user->orders->isEmpty()) {
                $order = new Order([
                    'total_price' => $competition->price,
                ]);

                $user->orders()->save($order);
                $this->createRegistration($user, $order, $competition, $data);
            } else {
                $order = $user->orders()->where([
                    ['status', OrderStatusEnum::Pending->value],
                    ['expired_at', '>', now()]
                ])->latest()->first();

                if (is_null($order)) {
                    $order = new Order([
                        'total_price' => $competition->price
                    ]);

                    $user->orders()->save($order);
                    $this->createRegistration($user, $order, $competition, $data);
                } else {
                    $order->total_price += $competition->price;
                    $this->createRegistration($user, $order, $competition, $data);
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

    private function createRegistration(
        User $user,
        Order $order,
        Competition $competition,
        array $data
    ) {
        $hasTeam = false;

        $registration = new Registration([
            'competition_id' => $competition->id,
            'user_id' => $user->uuid,
            'email' => $data['email'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'instagram' => $data['instagram'],
            'nickname' => $data['nickname'],
            'price' => $competition->price
        ]);

        $order->registrations()->save($registration);

        if ($competition->use_multi_participant) {
            $hasTeam = !is_null($data['teamName']) && count($data['teamMembers']) > 0;

            if ($hasTeam) {
                $teamMembers = [];
                $team = new Team([
                    'name' => $data['teamName'],
                    'number_of_members' => count($data['teamMembers'])
                ]);

                for ($i = 0; $i < $team->number_of_members; $i++) {
                    $teamMembers[] = new TeamMember([
                        'name' => $data['teamMembers'][$i]['name'],
                        'instagram' => $data['teamMembers'][$i]['instagram'],
                        'nickname' => $data['teamMembers'][$i]['nickname'],
                    ]);
                }

                $registration->team()->save($team);
                $team->members()->saveMany($teamMembers);
            }
        }

        // free pass ticket creation, 1 is for the leader
        if ($competition->with_ticket) $this->createTickets(
            $user,
            $order,
            $registration,
            $hasTeam
                ? $team->number_of_members + self::INDIVIDUAL_COUNT
                : self::INDIVIDUAL_COUNT
        );

        if ($order->isDirty('total_price')) $order->save();
    }

    private function createTickets(User $user, Order $order, Registration $registration, int $amount)
    {
        $activity = Activity::where('slug', self::TICKET_SLUG)->first();
        $tickets = [];

        for ($i = 0; $i < $amount; $i++) {
            $tickets[] = new Ticket([
                'activity_id' => $activity->id,
                'user_id' => $user->uuid,
                'registration_id' => $registration->id,
                'price' => self::PRICE_OF_FREE_PASS
            ]);

            $order->total_price += 0;
        }

        $order->tickets()->saveMany($tickets);
    }
}
