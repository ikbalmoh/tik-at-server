<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function transaction(Request $request): Response
    {
        return Inertia::render('Report/Transaction', [
            'transactions' => $this->getTransactions($request->from, $request->to),
            'dates' => [
                'from' => $request->from,
                'to' => $request->to,
            ],
            'summary' => $this->getTransactionSummary($request->from, $request->to)
        ]);
    }

    public function daily(Request $request): Response
    {
        return Inertia::render('Report/Daily', [
            'transactions' => $this->getDailyTransactions($request->from, $request->to),
            'dates' => [
                'from' => $request->from,
                'to' => $request->to,
            ],
            'summary' => $this->getTransactionSummary($request->from, $request->to)
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

    private function getTransactionSummary(string | null $from, string | null $to = ''): array
    {
        $transactions = TransactionDetail::selectRaw('
                SUM(qty) as qty,
                SUM(total) as total,
                MAX(created_at) as date_to,
                MIN(created_at) as date_from
            ');

        if ($from) {
            $transactions->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $transactions->whereDate('created_at', '<=', $to);
        }

        $sum = $transactions->first();

        return [
            'qty' => $sum->qty,
            'total' => $sum->total,
            'from' => date('d/m/Y', strtotime($sum->date_from)),
            'to' => date('d/m/Y', strtotime($sum->date_to)),
        ];
    }

    private function getDailyTransactions(string | null $from, string | null $to = ''): LengthAwarePaginator
    {
        $transactions = TransactionDetail::select('id')
            ->selectRaw('
            SUM(qty) as qty,
            DATE_FORMAT(created_at, "%d/%m/%Y") as date,
            SUM(total) as total
        ');

        if ($from) {
            $transactions->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $transactions->whereDate('created_at', '<=', $to);
        }

        return $transactions->groupBy('date')
            ->orderByDesc('created_at')
            ->paginate()
            ->appends(['from' => $from, 'to' => $to]);
    }
}
