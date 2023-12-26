<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    public function ticket(): Response
    {
        return Inertia::render('Setting/Ticket', [
            'tickets' => TicketType::all()
        ]);
    }

    public function users(): Response
    {
        return Inertia::render('Setting/Ticket', [
            'tickets' => TicketType::all()
        ]);
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        TicketType::create($request->validate([
            'name' => ['required'],
            'regular_price' => ['required'],
            'holiday_price' => ['required'],
            'is_active' => ['required'],
        ]));
        return to_route('ticket.index');
    }

    public function updateTicket($id, Request $request): RedirectResponse
    {
        $ticket = TicketType::find($id);
        $ticket->update($request->validate([
            'name' => ['required'],
            'regular_price' => ['required'],
            'holiday_price' => ['required'],
            'is_active' => ['required'],
        ]));
        return to_route('ticket.index');
    }

    public function destroyTicket($id): RedirectResponse
    {
        $ticket = TicketType::find($id);
        $ticket->delete();
        return to_route('ticket.index');
    }
}
