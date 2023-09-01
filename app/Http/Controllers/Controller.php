<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
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
                ['label' => 'Home', 'href' => '/']
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
}
