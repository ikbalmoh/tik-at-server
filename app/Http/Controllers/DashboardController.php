<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Inertia\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Dashboard', [
            'sales' => fn () => [
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
            ]
        ]);
    }
}
