<?php

namespace App\Checks;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

trait DownloadsUrlTrait
{
    protected function download(string $url)
    {
        if (Cache::has('url-'.$url))
            return Cache::get('url-'.$url);

        $lock = Cache::lock('lock-url-'.$url, 30);
        $lock->block(2);

        if (Cache::has('url-'.$url)) {
            $lock->release();
            return Cache::get('url-' . $url);
        }

        if (RateLimiter::tooManyAttempts('http', 10)) {
            $lock->release();

            throw new TooManyAttemptsException();
        }

        RateLimiter::hit('http', 15);

        try {
            $src = Http::get($url);

            $src = [
                'headers' => $src->headers(),
                'status' => $src->status(),
                'body' => $src->body(),
            ];

        } catch (\Exception $e) {

            Log::error($e->getMessage(), $e->getTrace());
        }

        $value = $src ?? [
            'headers' => null,
            'status' => null,
            'body' => null,
        ];

        Cache::put('url-'.$url, $value, now()->addDay());

        $lock->release();

        return $value;
    }
}
