<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping Server Monitor';

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

        $herders = [
            'X-Client-Key' => $clientKey,
        ];

        $ping = Http::withHeaders($herders)->get($serverUrl.'/api/ping');

        if ($ping->failed()) {
            $this->error($ping);
            return;
        } else {
            $this->info('✅ connected to server');
        }
    }
}
