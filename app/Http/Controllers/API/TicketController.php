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
}
