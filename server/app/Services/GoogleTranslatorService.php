<?php

namespace App\Services;

use Illuminate\Http\Request;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class GoogleTranslatorService
{
    protected TranslateClient $client;

    public function __construct()
    {
        $this->client = new TranslateClient([
            'key' => config('env.google_translate_api_key')
        ]);
    }

    public function translate(string $text, string $from, string $to): string
    {
        $result = '';
        try {
            if ($from === $to) {
                $result = $text;
            } else {
                $translation = $this->client->translate($text, [
                    'source' => $from,
                    'target' => $to
                ]);
                $result = $translation['text'];
            }
        } catch (Exception $e) {
            Log::error('Could not translate text due to: ' . $e->getMessage());
        }

        return $result;
    }
}
