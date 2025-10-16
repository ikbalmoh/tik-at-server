<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Traits\TransactionTrait;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'ticket_types' => TicketType::all(),
        ]);
    }

    public function monthly(Request $request): Response
    {
        $years = Transaction::selectRaw('YEAR(purchase_date) as year')->whereNotNull('purchase_date')->groupBy('year')->orderByDesc('year')->pluck('year');
        if (empty($years)) {
            $years = [date('Y')];
        }

        $months = [];
        for ($i=1; $i <= 12; $i++) { 
            $m = date('Y-m-d', mktime(0,0,0,$i, 1, date('Y')));
            $months[$i] = Carbon::parse($m)->translatedFormat('F');
        }

        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        return Inertia::render('Report/Daily', [
            'transactions' => $this->dailyTransactions($month, $year),
            'ticket_types' => TicketType::all(),
            'years' => $years,
            'months' => $months,
        ]);
    }

    public function download($year, $month)
    {
        $transactions = $this->dailyTransactions($month, $year);
        $types = TicketType::all();
        $total = [];
        foreach ($types as $type) {
            $total[$type->id] = 0;
        }
        foreach ($transactions as $key => $tr) {
            foreach ($types as $type) {
                $total[$type->id] += !empty($tr[$type->id]) ? $tr[$type->id] : 0;
            }
        }
        $data = [
            'transactions' => $transactions,
            'types' => $types,
            'year' => $year,
            'month' => $month,
            'period' => Carbon::parse($year.'-'.$month.'-01')->translatedFormat('F Y'),
            'total' => $total
        ];
        $pdf = Pdf::loadView('pdf.report', $data);
        return $pdf->download('data-pengunjung-'.$month.'-'.$year.'.pdf');
    }

}
