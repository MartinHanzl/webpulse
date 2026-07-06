<?php

namespace App\Jobs;

use App\Models\User\LoginLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResolveLoginLogLocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $loginLogId)
    {
    }

    public function handle(): void
    {
        $log = LoginLog::find($this->loginLogId);

        if (! $log || ! $log->ip_address) {
            return;
        }

        // Lokalitu nelze zjistit pro privátní/lokální IP (vývoj, interní síť)
        if (! filter_var($log->ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return;
        }

        try {
            $response = Http::timeout(3)->get('http://ip-api.com/json/'.$log->ip_address, [
                'fields' => 'status,country,city',
            ]);

            if (! $response->ok() || $response->json('status') !== 'success') {
                return;
            }

            $log->update([
                'country' => $response->json('country'),
                'city' => $response->json('city'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Login log geolocation failed: '.$e->getMessage());
        }
    }
}
