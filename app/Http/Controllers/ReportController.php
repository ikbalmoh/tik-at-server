<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use App\Traits\TransactionTrait;

class ReportController extends Controller
{
    use TransactionTrait;

    public function transaction(Request $request): Response
    {
        $from = !empty($request->from) ? $request->from : date('Y-m-').'01';
        $to = !empty($request->to) ? $request->to : date('Y-m-t');
        
        return Inertia::render('Report/Transaction', [
            'transactions' => $this->getTransactions($from, $to),
            'dates' => [
                'from' => $from,
                'to' => $to,
            ],
            'summary' => $this->transactionSummary($from, $to),
            'ticket_types' => TicketType::all()
        ]);
    }

    public function daily(Request $request): Response
    {
        $from = !empty($request->from) ? $request->from : date('Y-m-').'01';
        $to = !empty($request->to) ? $request->to : date('Y-m-t');

        return Inertia::render('Report/Daily', [
            'transactions' => $this->dailyTransactions($from, $to),
            'dates' => [
                'from' => $from,
                'to' => $to,
            ],
            'summary' => $this->transactionSummary($from, $to),
            'ticket_types' => TicketType::all()
        ]);
    }

    private function getTransactions(string | null $from, string | null $to = ''): LengthAwarePaginator
    {
        $transactions = Transaction::select('id', 'user_id', 'grand_total')
            ->selectRaw('
            DATE_FORMAT(created_at, "%d/%m/%Y %h:%i") as date
        ');

        if ($from) {
            $transactions->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $transactions->whereDate('created_at', '<=', $to);
        }

        return $transactions->orderByDesc('created_at')
            ->paginate()
            ->appends(['from' => $from, 'to' => $to]);
    }

}
