<?php

namespace App\Traits;

use App\Models\Transaction;
use App\Models\TicketType;
use App\Models\TransactionDetail;

trait TransactionTrait {
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

  public function dailyTransactions(string $from, string $to): array
    {
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
            SUM(total) as total
        ')->whereBetween('created_at', [$from, $to])
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
                'date' => $value->format('d/m/Y'),
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