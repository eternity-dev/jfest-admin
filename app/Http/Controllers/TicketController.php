<?php

namespace App\Http\Controllers;

use App\Enums\AttendStatusEnum;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::whereNot('code', null);
        $tickets = ($query = $request->query('search', false))
            ? $tickets
                ->where('code', 'like', '%' . $query . '%')
                ->orWhereRelation('user', 'email', 'like', '%' . $query . '%')
                ->orWhereRelation('user', 'name', 'like', '%' . $query . '%')
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

    public function edit(Request $request, Ticket $ticket)
    {
        if ($query = $request->query('state', false)) {
            if ($query != 'mark-as-attended') return redirect()->back();

            $ticket->attended_status = AttendStatusEnum::Attended;
            $ticket->save();

            return redirect()->back();
        }
    }

    public function update()
    {

    }
}
