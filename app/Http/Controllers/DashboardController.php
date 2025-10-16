<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Dashboard', [
            'sales' => fn() => $this->salesSummary(),
            'chart' => fn() => $this->salesChart(),
            'ticketTypes' => fn() => TicketType::where('is_active', true)->get(),
            'colors' => config('ticket.colors')
        ]);
    }

    private function salesSummary(): array
    {
        $types = TicketType::all();

        $summary = [
            'day' => [
                'all' => DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereDate('purchase_date', date('Y-m-d'))->sum('qty'),
            ],
            'week' => [
                'all' => DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereDate('purchase_date', '>', date('Y-m-d', strtotime('-7 days')))->sum('qty'),
            ],
            'month' => [
                'all' => DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereMonth('purchase_date', date('m'))->whereYear('purchase_date', date('Y'))->sum('qty'),
            ],
            'year' => [
                'all' => DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereYear('purchase_date', date('Y'))->sum('qty'),
            ]
        ];

        foreach ($types as $key => $ticket) {
            $summary['day'][$ticket->id] = DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereDate('purchase_date', date('Y-m-d'))->where('ticket_type_id', $ticket->id)->sum('qty');
            $summary['week'][$ticket->id] = DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereDate('purchase_date', '>', date('Y-m-d', strtotime('-7 days')))->where('ticket_type_id', $ticket->id)->sum('qty');
            $summary['month'][$ticket->id] = DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereMonth('purchase_date', date('m'))->whereYear('purchase_date', date('Y'))->where('ticket_type_id', $ticket->id)->sum('qty');
            $summary['year'][$ticket->id] = DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereYear('purchase_date', date('Y'))->where('ticket_type_id', $ticket->id)->sum('qty');
        }

        return $summary;
    }

    private function getGroupedTransaction(int $ticketTypeId = null, string $dateFrom = '', string $dateFormat = "%d/%m/%Y"): array
    {
        $data = DB::table('transaction_details as d')->join('transactions as t', 't.id', 'd.transaction_id')->whereDate('purchase_date', '>=', $dateFrom);

        if ($ticketTypeId) {
            $data->where('ticket_type_id', $ticketTypeId);
        }

        $data->select('ticket_type_id')
            ->selectRaw('DATE_FORMAT(purchase_date, "' . $dateFormat . '") as date, SUM(qty) as total')
            ->groupBy('date');

        return $data->pluck('total', 'date')->toArray();
    }

    private function salesChart()
    {
        $types = TicketType::all();

        $data = [
            'daily' => [],
            'monthly' => []
        ];

        foreach ($types as $key => $type) {
            $data['daily'][$type->id] = [];
            $data['monthly'][$type->id] = [];
        }

        $daily_from = date('Y-m-d', strtotime('-1 month'));
        $monthly_from = date('Y-m-d', strtotime('-12 month'));

        $daily = $this->getGroupedTransaction(null, $daily_from);
        $monthly = $this->getGroupedTransaction(null, $monthly_from, '%m/%Y');

        $labels = [
            'daily' => array_keys($daily),
            'monthly' => array_keys($monthly),
        ];

        $datasets = [
            'daily' => [],
            'monthly' => [],
        ];

        $data_daily = [];
        $data_monthly = [];
        foreach ($types as $key => $type) {
            $data_daily[$type->id] = $this->getGroupedTransaction($type->id, $daily_from);
            $data_monthly[$type->id] = $this->getGroupedTransaction($type->id, $monthly_from, '%m/%Y');
        }

        foreach ($labels['daily'] as $date) {
            foreach ($types as $key => $type) {
                if (isset($data_daily[$type->id][$date])) {
                    $data['daily'][$type->id][] = (float)$data_daily[$type->id][$date];
                }
            }
        }

        foreach ($labels['monthly'] as $date) {
            foreach ($types as $key => $type) {
                if (isset($data_monthly[$type->id][$date])) {
                    $data['monthly'][$type->id][] = (float)$data_monthly[$type->id][$date];
                }
            }
        }

        foreach ($types as $key => $type) {
            $datasets['daily'][] =
                [
                    'label' => $type->name,
                    'data' => $data['daily'][$type->id],
                    'backgroundColor' => config('ticket.colors')[$key] ?? '',
                    'borderRadius' => 99,
                    'borderSkipped' => false
                ];
                
            $datasets['monthly'][] =
                [
                    'label' => $type->name,
                    'data' => $data['monthly'][$type->id],
                    'backgroundColor' => config('ticket.colors')[$key] ?? '#118ab2',
                    'borderRadius' => 99,
                    'borderSkipped' => false
                ];
        }


        return [
            'daily' => [
                'labels' => $labels['daily'],
                'datasets' => $datasets['daily']
            ],
            'monthly' => [
                'labels' => $labels['monthly'],
                'datasets' => $datasets['monthly']
            ],
        ];
    }
}
