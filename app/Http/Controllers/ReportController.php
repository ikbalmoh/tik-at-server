<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function transaction(): Response
    {
        return Inertia::render('Report/Transaction', [
            'transactions' => Transaction::select('id', 'user_id', 'grand_total')
                ->selectRaw('
                    DATE_FORMAT(created_at, "%d/%m/%Y") as date
                ')
                ->orderByDesc('created_at')
                ->paginate()
        ]);
    }

    public function daily(): Response
    {
        return Inertia::render('Report/Daily', [
            'transactions' => Transaction::select('id')
                ->selectRaw('
                    DATE_FORMAT(created_at, "%d/%m/%Y") as date,
                    SUM(grand_total) as grand_total
                ')
                ->groupBy('date')
                ->orderByDesc('created_at')
                ->paginate()
        ]);
    }
}
