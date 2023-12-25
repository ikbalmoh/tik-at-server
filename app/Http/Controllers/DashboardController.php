<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use Inertia\Response;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Dashboard', [
            'sales' => fn () => $this->salesSummary(),
            'chart' => fn () => $this->salesChart()
        ]);
    }

    private function salesSummary(): array
    {
        return [
            'day' => [
                'all' => TransactionDetail::whereDate('created_at', date('Y-m-d'))->sum('qty'),
                'dewasa' => TransactionDetail::whereDate('created_at', date('Y-m-d'))->where('ticket_type_id', 1)->sum('qty'),
                'anak' => TransactionDetail::whereDate('created_at', date('Y-m-d'))->where('ticket_type_id', 2)->sum('qty'),
                'mancanegara' => TransactionDetail::whereDate('created_at', date('Y-m-d'))->where('ticket_type_id', 3)->sum('qty'),
            ],
            'week' => [
                'all' => TransactionDetail::whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->sum('qty'),
                'dewasa' => TransactionDetail::whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->where('ticket_type_id', 1)->sum('qty'),
                'anak' => TransactionDetail::whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->where('ticket_type_id', 2)->sum('qty'),
                'mancanegara' => TransactionDetail::whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->where('ticket_type_id', 3)->sum('qty')
            ],
            'month' => [
                'all' => TransactionDetail::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('qty'),
                'dewasa' => TransactionDetail::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('ticket_type_id', 1)->sum('qty'),
                'anak' => TransactionDetail::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('ticket_type_id', 2)->sum('qty'),
                'mancanegara' => TransactionDetail::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('ticket_type_id', 3)->sum('qty')
            ],
            'year' => [
                'all' => TransactionDetail::whereYear('created_at', date('Y'))->sum('qty'),
                'dewasa' => TransactionDetail::whereYear('created_at', date('Y'))->where('ticket_type_id', 1)->sum('qty'),
                'anak' => TransactionDetail::whereYear('created_at', date('Y'))->where('ticket_type_id', 2)->sum('qty'),
                'mancanegara' => TransactionDetail::whereYear('created_at', date('Y'))->where('ticket_type_id', 3)->sum('qty')
            ]
        ];
    }

    private function getGroupedTransaction(int $ticketTypeId = null, string $dateFrom = '', string $dateFormat = "%d/%m/%Y"): array
    {
        $data = TransactionDetail::whereDate('created_at', '>=', $dateFrom);

        if ($ticketTypeId) {
            $data->where('ticket_type_id', $ticketTypeId);
        }

        $data->select('ticket_type_id')
            ->selectRaw('DATE_FORMAT(created_at, "' . $dateFormat . '") as date, SUM(qty) as total')
            ->groupBy('date');

        return $data->pluck('total', 'date')->toArray();
    }

    private function salesChart()
    {
        $data = [
            'daily' => [
                'dewasa' => [],
                'anak' => [],
                'mancanegara' => [],
            ],
            'monthly' => [
                'dewasa' => [],
                'anak' => [],
                'mancanegara' => [],
            ]
        ];

        $daily_from = date('Y-m-d', strtotime('-1 month'));
        $monthly_from = date('Y-m-d', strtotime('-12 month'));

        $daily = $this->getGroupedTransaction(null, $daily_from);
        $monthly = $this->getGroupedTransaction(null, $monthly_from, '%m/%Y');

        $labels = [
            'daily' => array_keys($daily),
            'monthly' => array_keys($monthly),
        ];

        $daily_dewasa = $this->getGroupedTransaction(1, $daily_from);
        $daily_anak = $this->getGroupedTransaction(2, $daily_from);
        $daily_mancanegara = $this->getGroupedTransaction(3, $daily_from);
        foreach ($labels['daily'] as $date) {
            $total_d = 0;
            $total_a = 0;
            $total_m = 0;
            if (isset($daily_dewasa[$date])) {
                $total_d = $daily_dewasa[$date];
            }
            if (isset($daily_anak[$date])) {
                $total_a = $daily_anak[$date];
            }
            if (isset($daily_mancanegara[$date])) {
                $total_m = $daily_mancanegara[$date];
            }
            $data['daily']['dewasa'][] = $total_d;
            $data['daily']['anak'][] = $total_a;
            $data['daily']['mancanegara'][] = $total_m;
        }

        $monthly_dewasa = $this->getGroupedTransaction(1, $monthly_from, '%m/%Y');
        $monthly_anak = $this->getGroupedTransaction(2, $monthly_from, '%m/%Y');
        $monthly_mancanegara = $this->getGroupedTransaction(3, $monthly_from, '%m/%Y');
        foreach ($labels['monthly'] as $date) {
            $total_d = 0;
            $total_a = 0;
            $total_m = 0;
            if (isset($monthly_dewasa[$date])) {
                $total_d = $monthly_dewasa[$date];
            }
            if (isset($monthly_anak[$date])) {
                $total_a = $monthly_anak[$date];
            }
            if (isset($monthly_mancanegara[$date])) {
                $total_m = $monthly_mancanegara[$date];
            }
            $data['monthly']['dewasa'][] = $total_d;
            $data['monthly']['anak'][] = $total_a;
            $data['monthly']['mancanegara'][] = $total_m;
        }


        $datasets = [
            'daily' => [
                [
                    'label' => "Semua",
                    'data' => array_values($daily),
                    'backgroundColor' => "#66bb86",
                ],
                [
                    'label' => "Dewasa",
                    'data' => $data['daily']['dewasa'],
                    'backgroundColor' => "#7bb5ff",
                ],
                [
                    'label' => "Anak-anak",
                    'data' => $data['daily']['anak'],
                    'backgroundColor' => "#ff95c9",
                ],
                [
                    'label' => "Mancanegara",
                    'data' => $data['daily']['mancanegara'],
                    'backgroundColor' => "#ff8f61",
                ],
            ],
            'monthly' => [
                [
                    'label' => "Semua",
                    'data' => array_values($monthly),
                    'backgroundColor' => "#66bb86",
                ],
                [
                    'label' => "Dewasa",
                    'data' => $data['monthly']['dewasa'],
                    'backgroundColor' => "#7bb5ff",
                ],
                [
                    'label' => "Anak-anak",
                    'data' => $data['monthly']['anak'],
                    'backgroundColor' => "#ff95c9",
                ],
                [
                    'label' => "Mancanegara",
                    'data' => $data['monthly']['mancanegara'],
                    'backgroundColor' => "#ff8f61",
                ],
            ],
        ];

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
