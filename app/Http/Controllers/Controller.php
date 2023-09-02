<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function withLinks($extendedLinks = [])
    {
        return [
            'auth_url' => [
                'attempt' => route('auth.attempt.index'),
                'revoke' => route('auth.revoke')
            ],
            'navbar_url' => [
                ['label' => 'Home', 'href' => route('organizer.home')],
                ['label' => 'Order', 'href' => route('organizer.order.index')],
                ['label' => 'Ticket', 'href' => route('organizer.ticket.index')],
                ['label' => 'Registration', 'href' => route('organizer.registration.index')]
            ],
            ...$extendedLinks
        ];
    }

    protected function withMetadata($extendedMetadata = [])
    {
        return [
            ...$extendedMetadata
        ];
    }

    protected function withUser(Request $request)
    {
        return [
            'user' => $request->user()
        ];
    }
}
