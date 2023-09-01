<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::whereNot('code', null);
        $tickets = $request->has('search')
            ? $tickets
                ->where('code', 'like', '%' . $request->query('search') . '%')
                ->orWhereRelation('user', 'email', 'like', '%' . $request->query('search') . '%')
            : $tickets;

        return view('tickets.index', [
            'title' => 'Ticket',
            'data' => [
                'tickets' => $tickets->paginate(25),
            ],
            ...$this->withLinks([]),
            ...$this->withMetadata([
                'search_query' => $request->query('search', null)
            ]),
            ...$this->withUser($request)
        ]);
    }
}
