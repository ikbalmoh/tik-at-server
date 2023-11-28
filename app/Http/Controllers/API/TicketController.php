<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function types(): JsonResponse
    {
        $types = TicketType::where('is_active', 1)->get();
        return response()->json($types);
    }

    public function show($id): JsonResponse
    {
        $data = [
            'ticket' => null,
            'message' => 'Kode tiket tidak valid',
        ];

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json($data, 404);
        }

        $data['ticket'] = [
            'id' => $ticket->id,
            'entrance_max' => $ticket->entrance_max,
            'entrance_count' => $ticket->entrance_count,
            'expires_at' => $ticket->expires_at,
            'ticket_type_name' => $ticket->ticket_type_name,
            'ticket_price' => $ticket->ticket_price,
            'purchase_date' => $ticket->transactionDetail->transaction->created_at,
            'is_group' => $ticket->transactionDetail->transaction->is_group,
            'gate' => $ticket->transactionDetail->transaction->gate,
        ];

        $data['message'] = 'Tiket ditemukan';

        return response()->json($data);
    }

    public function entrance(Request $request, $id): JsonResponse
    {
        $data = [
            'ticket' => null,
            'message' => 'Tiket tidak ditemukan',
            'valid' => false,
        ];

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json($data, 404);
        }

        $code = 200;

        if ($ticket->entrance_count < $ticket->entrance_max) {
            $data['message'] = 'Tiket valid';
            $data['valid'] = true;

            if ($request->door) {
                $ticket->entrance_door = $request->door;
            }
            if ($request->gate) {
                $ticket->entrance_gate = $request->gate;
            }

            $ticket->entrance_count = $ticket->entrance_count + 1;
            $ticket->save();
        } else {
            $code = 403;
            $data['message'] = 'Tiket sudah tidak berlaku';
        }

        $data['ticket'] = [
            'id' => $ticket->id,
            'entrance_max' => $ticket->entrance_max,
            'entrance_count' => $ticket->entrance_count,
            'expires_at' => $ticket->expires_at,
            'ticket_type_name' => $ticket->ticket_type_name,
            'ticket_price' => $ticket->ticket_price,
            'purchase_date' => $ticket->transactionDetail->transaction->created_at,
            'is_group' => $ticket->transactionDetail->transaction->is_group,
            'gate' => $ticket->transactionDetail->transaction->gate,
        ];

        return response()->json($data, $code);
    }
}
