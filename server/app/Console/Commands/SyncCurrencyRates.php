<?php

namespace App\Console\Commands;

use App\Models\Currency\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncCurrencyRates extends Command
{
    protected $signature = 'currency:sync-rates';

    protected $description = 'Sync currency exchange rates from TwelveData (real-time forex).';

    public function handle(): int
    {
        $apiKey = config('services.twelvedata.key');
        $base = strtoupper(config('services.twelvedata.base_currency', 'CZK'));

        if (empty($apiKey)) {
            $this->error('TWELVEDATA_API_KEY is not configured.');
            return self::FAILURE;
        }

        $currencies = Currency::query()
            ->whereRaw('UPPER(code) != ?', [$base])
            ->get(['id', 'code']);

        if ($currencies->isEmpty()) {
            $this->info("No non-base currencies to sync (base = {$base}).");
            $this->ensureBaseRate($base);
            return self::SUCCESS;
        }

        $symbols = $currencies
            ->map(fn ($c) => strtoupper($c->code) . '/' . $base)
            ->implode(',');

        $response = Http::timeout(15)->get('https://api.twelvedata.com/exchange_rate', [
            'symbol' => $symbols,
            'apikey' => $apiKey,
        ]);

        if (!$response->successful()) {
            Log::error('TwelveData request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $this->error('TwelveData request failed: HTTP ' . $response->status());
            return self::FAILURE;
        }

        $data = $response->json();

        if (isset($data['code']) && $data['code'] >= 400) {
            Log::error('TwelveData API error', $data);
            $this->error('TwelveData API error: ' . ($data['message'] ?? 'unknown'));
            return self::FAILURE;
        }

        $rates = $this->normalizeResponse($data, $currencies->count());
        $updated = 0;

        foreach ($currencies as $currency) {
            $key = strtoupper($currency->code) . '/' . $base;
            if (!isset($rates[$key])) {
                $this->warn("Missing rate for {$key}");
                continue;
            }
            $currency->fill(['rate' => $rates[$key]])->save();
            $updated++;
        }

        $this->ensureBaseRate($base);
        $this->info("Updated {$updated}/{$currencies->count()} currencies against {$base}.");

        return self::SUCCESS;
    }

    private function normalizeResponse(array $data, int $expectedCount): array
    {
        $rates = [];

        if ($expectedCount === 1 && isset($data['symbol'], $data['rate'])) {
            $rates[$data['symbol']] = (float) $data['rate'];
            return $rates;
        }

        foreach ($data as $key => $value) {
            if (is_array($value) && isset($value['rate'])) {
                $symbol = $value['symbol'] ?? $key;
                $rates[$symbol] = (float) $value['rate'];
            }
        }

        return $rates;
    }

    private function ensureBaseRate(string $base): void
    {
        Currency::query()
            ->whereRaw('UPPER(code) = ?', [$base])
            ->update(['rate' => 1]);
    }
}
