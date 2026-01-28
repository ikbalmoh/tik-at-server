<?php

namespace App\Traits;

use App\Models\TicketType;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

trait TransactionTrait
{
    public function getTransactions(string|null $from, string|null $to = ''): LengthAwarePaginator
    {
        $transactions = DB::table('transactions')
            ->select('id', 'user_id', 'grand_total')
            ->selectRaw('
            DATE_FORMAT(purchase_date, "%d/%m/%Y %h:%i") as date
        ');

        if ($from) {
            $transactions->whereDate('purchase_date', '>=', $from);
        }
        if ($to) {
            $transactions->whereDate('purchase_date', '<=', $to);
        }

        return $transactions->orderByDesc('purchase_date')
            ->paginate()
            ->appends(['from' => $from, 'to' => $to]);
    }

    public function transactionSummary(string|null $from, string|null $to = ''): array
    {
        $transactions = DB::table('transaction_details as td')
            ->join('transactions as t', 't.id', 'td.transaction_id')
            ->selectRaw('
                SUM(td.qty) as qty,
                SUM(td.total) as total,
                MAX(t.purchase_date) as date_to,
                MIN(t.purchase_date) as date_from
            ');

        if ($from) {
            $transactions->whereDate('t.purchase_date', '>=', $from);
        }
        if ($to) {
            $transactions->whereDate('t.purchase_date', '<=', $to);
        }

        $sum = $transactions->first();

        return [
            'qty' => $sum->qty,
            'total' => $sum->total,
            'from' => date('d M Y', strtotime($from ?? $sum->date_from)),
            'to' => date('d M Y', strtotime($to ?? $sum->date_to)),
        ];
    }

    public function dailyTransactions(string $month, string $year): array
    {
        $from = implode('-', [$year, $month, '01']);
        $to = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
        $period = new \DatePeriod(
            new \DateTime($from),
            new \DateInterval('P1D'),
            new \DateTime(date('Y-m-d', strtotime($to . ' +1 day')))
        );

        $transactions = DB::table('transaction_details as d')
            ->join('transactions as t', 't.id', 'd.transaction_id')
            ->select('d.id')
            ->selectRaw('
            SUM(d.qty) as qty,
            d.ticket_type_id,
            DATE_FORMAT(t.purchase_date, "%Y-%m-%d") as date,
            DATE_FORMAT(t.purchase_date, "%d") as day,
            SUM(d.total) as total
        ')->whereYear('t.purchase_date', $year)->whereMonth('t.purchase_date', $month)
            ->groupBy('date')->groupBy('ticket_type_id')
            ->get();


        $ticketTypes = TicketType::pluck('id');

        $groupByDate = [];
        foreach ($transactions as $key => $tr) {
            if (!isset($groupByDate[$tr->date])) {
                $groupByDate[$tr->date] = [
                    'total' => 0,
                    'totals' => []
                ];
            }
            $groupByDate[$tr->date]['total'] += $tr->total;
            $groupByDate[$tr->date]['totals'][] = $tr->total;
            $groupByDate[$tr->date][$tr->ticket_type_id] = $tr->qty;
        }

        $data = [];
        foreach ($period as $key => $value) {
            $transaction = [
                'date' => $value->format('Y-m-d'),
                'day' => $value->format('j'),
                'total' => 0,
                'totals' => [],
            ];
            if (isset($groupByDate[$value->format('Y-m-d')])) {
                $dayTransaction = $groupByDate[$value->format('Y-m-d')];
                $transaction['total'] = $dayTransaction['total'];
                $transaction['totals'] = $dayTransaction['totals'];
                foreach ($ticketTypes as $key => $type) {
                    $transaction[$type] = !empty($dayTransaction[$type]) ? (int) $dayTransaction[$type] : 0;
                }
            }
            $data[] = $transaction;
        }

        return $data;
    }
}