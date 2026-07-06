<?php

namespace App\Services;

use App\Jobs\ResolveLoginLogLocation;
use App\Models\User\LoginLog;
use Illuminate\Http\Request;

class LoginLogService
{
    public static function record(Request $request, ?int $userId, ?string $email, bool $successful): LoginLog
    {
        $userAgent = (string) $request->userAgent();
        $parsed = self::parseUserAgent($userAgent);

        $log = LoginLog::create([
            'user_id' => $userId,
            'email' => $email,
            'successful' => $successful,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent !== '' ? $userAgent : null,
            'browser' => $parsed['browser'],
            'browser_version' => $parsed['browser_version'],
            'platform' => $parsed['platform'],
            'device_type' => $parsed['device_type'],
        ]);

        ResolveLoginLogLocation::dispatchAfterResponse($log->id);

        return $log;
    }

    private static function parseUserAgent(string $userAgent): array
    {
        $browser = null;
        $version = null;

        $browsers = [
            'Edge' => '/Edg(?:e|A|iOS)?\/([\d.]+)/',
            'Opera' => '/(?:OPR|Opera)\/([\d.]+)/',
            'Samsung Internet' => '/SamsungBrowser\/([\d.]+)/',
            'Chrome' => '/(?:Chrome|CriOS)\/([\d.]+)/',
            'Firefox' => '/(?:Firefox|FxiOS)\/([\d.]+)/',
            'Safari' => '/Version\/([\d.]+).*Safari/',
            'Internet Explorer' => '/(?:MSIE ([\d.]+)|Trident.*rv:([\d.]+))/',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $matches)) {
                $browser = $name;
                $version = $matches[1] !== '' ? $matches[1] : ($matches[2] ?? null);
                break;
            }
        }

        $platform = match (true) {
            (bool) preg_match('/Windows NT/i', $userAgent) => 'Windows',
            (bool) preg_match('/iPhone|iPad|iPod/i', $userAgent) => 'iOS',
            (bool) preg_match('/Macintosh|Mac OS X/i', $userAgent) => 'macOS',
            (bool) preg_match('/Android/i', $userAgent) => 'Android',
            (bool) preg_match('/Linux/i', $userAgent) => 'Linux',
            default => null,
        };

        $deviceType = match (true) {
            (bool) preg_match('/bot|crawler|spider|curl|wget/i', $userAgent) => 'bot',
            (bool) preg_match('/iPad|Tablet/i', $userAgent)
                || (preg_match('/Android/i', $userAgent) && ! preg_match('/Mobile/i', $userAgent)) => 'tablet',
            (bool) preg_match('/Mobi|iPhone|iPod/i', $userAgent) => 'mobile',
            $userAgent === '' => null,
            default => 'desktop',
        };

        return [
            'browser' => $browser,
            'browser_version' => $version !== null ? mb_substr($version, 0, 32) : null,
            'platform' => $platform,
            'device_type' => $deviceType,
        ];
    }
}
