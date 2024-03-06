<?php

namespace App\Traits;

use App\Models\Transaction;
use App\Models\TicketType;
use App\Models\TransactionDetail;
use Illuminate\Pagination\LengthAwarePaginator;

trait TransactionTrait {
    public function getTransactions(string | null $from, string | null $to = ''): LengthAwarePaginator
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

  public function transactionSummary(string | null $from, string | null $to = ''): array
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
            'from' => date('d M Y', strtotime($from ?? $sum->date_from)),
            'to' => date('d M Y', strtotime($to ?? $sum->date_to)),
        ];
    }

  public function dailyTransactions(string $month, string $year): array
    {
        $from = implode('-', [$year, $month, '01']);
        $to = date('Y-m-t', mktime(0,0,0,$month,1,$year));
        $period = new \DatePeriod(
            new \DateTime($from),
            new \DateInterval('P1D'),
            new \DateTime(date('Y-m-d', strtotime($to.' +1 day')))
       );

        $transactions = TransactionDetail::select('id')
            ->selectRaw('
            SUM(qty) as qty,
            ticket_type_id,
            DATE_FORMAT(created_at, "%Y-%m-%d") as date,
            DATE_FORMAT(created_at, "%d") as day,
            SUM(total) as total
        ')->whereYear('created_at', $year)->whereMonth('created_at', $month)
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
                    $transaction[$type] = !empty($dayTransaction[$type]) ? (int)$dayTransaction[$type] : 0;
                }
            }
            $data[] = $transaction;
        }

        return $data;
    }
}