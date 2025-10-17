<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Transactions to Server Monitor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serverUrl = config('app.server_url');
        $clientKey = config('app.client_key');

        $this->line($serverUrl ? '✅ Server Url Set '.$serverUrl : '❗️ Server Url Not Set');
        $this->line($clientKey ? '✅ Client Key Set' : '❗️ Client Key Not Set');

        if (!$serverUrl || !$clientKey) {
            return;
        }

        $headers = [
            'X-Client-Key' => $clientKey,
        ];

        $ping = Http::withHeaders($headers)->get($serverUrl.'/api/ping');

        if ($ping->failed()) {
            $this->error($ping);
            return;
        } else {
            $this->info('✅ connected to server');
        }
        
        $transactions = DB::table('transactions as t')
            ->select(
                't.id',
                't.purchase_date',
                't.is_group',
                't.note',
                't.pay',
                't.charge',
                't.payment_method',
                't.payment_ref',
                't.grand_total'
            )
            ->whereNull('t.synced_at')
            ->get()
            ->map(function($transaction) {
                $details = DB::table('transaction_details as d')
                    ->leftJoin('ticket_types as tt', 'tt.id', '=', 'd.ticket_type_id')
                    ->select(
                        DB::raw('tt.name as ticket_name'),
                        'price', 'qty', 'discount', 'subtotal', 'total'
                    )
                    ->where('d.transaction_id', $transaction->id)->get();
                $transaction->tickets = $details;
                return $transaction;
            });

        $totalTransactions = count($transactions);
        if ($totalTransactions == 0) {
            $this->info('no transactions to sync');
            return;
        }

        $payload = $transactions->map(fn ($t) => (array) $t)->toArray();

        $this->info('syncing '.$totalTransactions.' transactions');

        $syncRes = Http::withHeaders($headers)
            ->asJson()
            ->withoutRedirecting()
            ->post($serverUrl.'/api/transactions', $payload);

        if ($syncRes->failed()) {
            $this->error($syncRes);
            return;
        } else {
            $this->info('✅ sync finished');
            $this->info($syncRes);
            $synced = array_merge($syncRes['synced'], $syncRes['skipped']);
            if (count($synced) > 0) {
                DB::table('transactions')->whereIn('id', $synced)->update(['synced_at' => Carbon::now()]);
            }
        }
    }
}
