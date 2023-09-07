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
                ['label' => 'Order', 'href' => route('dashboard.orders.index')],
                ['label' => 'User', 'href' => route('dashboard.users.index')],
                ['label' => 'Export', 'href' => route('dashboard.exports.index')]
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
            'auth' => $request->user()
        ];
    }
}
