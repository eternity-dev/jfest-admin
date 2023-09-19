<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        if (!is_null($query = $request->query('search', null))) {
            $registrations = Registration::whereIsRegistered()
                ->orWhereRelation('competition', 'name', 'like', '%' . $query . '%')
                ->orWhereRelation('user', 'email', 'like', '%' . $query . '%')
                ->orWhereRelation('user', 'name', 'like', '%' . $query . '%')
                ->orWhereRelation('team', 'name', 'like', '%' . $query . '%')
                ->paginate(25);
        } else if (!is_null($queryByStatus = $request->query('status', null))) {
            $registrations = Registration::where('status', $queryByStatus)->paginate(25);
        } else { $registrations = Registration::paginate(25); }

        return view('registrations.index', [
            'data' => [
                'registrations' => $registrations
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'search_query' => $query
            ]),
            ...$this->withUser($request)
        ]);
    }
}
