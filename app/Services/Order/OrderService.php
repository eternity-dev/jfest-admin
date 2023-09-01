<?php

namespace App\Services\Order;

use App\Models\Activity;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Support\Collection;

class OrderService
{
    public function createRegistrationOrder(User $user, Competition $competition, array $data)
    {
        return (new CreateRegistrationService)->handle($user, $competition, $data);
    }

    public function createTicketsOrder(User $user, Activity $activity, int $amount)
    {
        return (new CreateTicketService)->handle($user, $activity, $amount);
    }

    public function remapTickets(Collection &$tickets)
    {
        return (new RemapTicketsService)->handle($tickets);
    }

    public function remapRegistrations(Collection &$registrations)
    {
        return (new RemapRegistrationsService)->handle($registrations);
    }
}
