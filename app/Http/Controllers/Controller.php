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
                ['label' => 'Overview', 'href' => route('dashboard.home.index')],
                ['label' => 'Order', 'href' => '/'],
                ['label' => 'Ticket', 'href' => '/'],
                ['label' => 'Registration', 'href' => '/']
            ],
            ...$extendedLinks
        ];
    }

    protected function withMetadata($extendedMetadata = [])
    {
        return [
            'meta' => [
                ...$extendedMetadata
            ]
        ];
    }

    protected function withUser(Request $request)
    {
        return [
            'user' => $request->user()
        ];
    }
}
