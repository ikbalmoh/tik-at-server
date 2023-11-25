<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaction;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(StoreTransaction $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $payload = $request->only([
                'type_id',
                'is_group',
                'qty',
                'price',
                'subtotal',
                'discount',
                'total',
                'pay',
                'charge',
                'payment_method'
            ]);
            $payload['operator_id'] = $user->id;

            $transaction = Transaction::create($payload);

            $data_tickets = [];

            $total_ticket = $transaction->is_group ? 1 : $transaction->qty;
            $entrance_max = $transaction->is_group ? $transaction->qty : 1;
            for ($i = 0; $i < $total_ticket; $i++) {
                $data_tickets[] = new Ticket(
                    [
                        'entrance_max' => $entrance_max,
                    ]
                );
            }

            $tickets = $transaction->tickets()->saveMany($data_tickets);

            $data = [
                'transaction' => $transaction,
                'tickets' => $tickets,
                'message' => 'Ticket published'
            ];

            DB::commit();

            return response()->json($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }
}
