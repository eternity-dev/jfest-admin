<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = Registration::whereNot('uuid', null);
        $registrations = ($query = $request->query('search', false))
            ? $registrations
                ->whereRelation('competition', 'name', 'like', '%' . $query . '%')
                ->orWhereRelation('user', 'email', 'like', '%' . $query . '%')
            : $registrations;

        return view('registrations.index', [
            'data' => [
                'registrations' => $registrations->paginate(25)
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'search_query' => $query
            ]),
            ...$this->withUser($request)
        ]);
    }
}
