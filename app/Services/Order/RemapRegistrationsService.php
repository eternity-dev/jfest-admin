<?php

namespace App\Services\Order;

use App\Models\Registration;
use App\Services\Contract\RemapOrderItems;
use Illuminate\Support\Collection;

class RemapRegistrationsService implements RemapOrderItems
{
    public function handle(?Collection &$items): void
    {
        if (is_null($items)) {
            return;
        }

        $items = $items->map(function (Registration $registration) {
            $date = $registration->competition->registration_closed_at;
            $registration->competition->registrationCloseAtStr = $date->diffForHumans();
            $registration->remove_url = route('user.order.competition.remove', [
                'registration' => $registration
            ]);

            return $registration;
        });
    }
}
