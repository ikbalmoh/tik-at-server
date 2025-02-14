<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaction;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(StoreTransaction $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $trx_payload = $request->only([
                'is_group',
                'grand_total',
                'pay',
                'charge',
                'payment_method',
                'payment_ref',
                'purchase_date'
            ]);
            $trx_payload['user_id'] = $user->id;

            $transaction = Transaction::create($trx_payload);

            $qty = 0;

            $trx_payload_details = [];
            foreach ($request->tickets as $key => $t) {
                $qty += $t['qty'];
                $trx_payload_details[] = new TransactionDetail($t);
            }

            $transaction->details()->saveMany($trx_payload_details);

            $expires = Carbon::today()->setTime(17, 0, 0)->toDateTimeString();

            $data_tickets = [];
            if ($transaction->is_group) {
                $detail = $transaction->details[0];
                Ticket::create([
                    'transaction_detail_id' => $detail->id,
                    'expires_at' => $expires,
                    'entrance_max' => $qty
                ]);
            } else {
                foreach ($transaction->details as $key => $detail) {
                    for ($i = 0; $i < $detail->qty; $i++) {
                        $data_tickets[] = new Ticket([
                            'expires_at' => $expires,
                        ]);
                    }
                    $detail->tickets()->saveMany($data_tickets);
                }
            }

            $ticket_transaction = [
                'is_group' => $transaction->is_group,
                'purchase_date' => $transaction->purchase_date,
                'gate' => $transaction->gate,
                'operator_name' => $transaction->operator->name,
            ];
            foreach ($transaction->tickets as $key => $ticket) {
                $tickets[] = array_merge([
                    'id' => $ticket->id,
                    'entrance_max' => $ticket->entrance_max,
                    'entrance_count' => $ticket->entrance_count,
                    'expires_at' => $ticket->expired_at,
                    'ticket_type_name' => $ticket->ticket_type_name,
                    'ticket_price' => $ticket->ticket_price
                ], $ticket_transaction,);
            }

            $receipt = Transaction::with('details')->find($transaction->id);

            $data = [
                'transaction' => $receipt,
                'tickets' => $tickets,
                'message' => 'Transaction success'
            ];

            DB::commit();

            return response()->json($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage(),
            ]);
        }
    }
}
