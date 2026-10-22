<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       if (app()->environment('local')) {
    Http::globalResponseMiddleware(
        function (ResponseInterface $response): ResponseInterface {
            if ($response->getStatusCode() === 429) {
                $body = $response->getBody();

                $position = $body->isSeekable()
                    ? $body->tell()
                    : null;

                $errorBody = $body->getContents();

                // Kembalikan posisi stream agar Prism tetap bisa membacanya.
                if ($position !== null) {
                    $body->seek($position);
                }

                Log::warning('DEBUG PROVIDER HTTP 429', [
                    'status' => $response->getStatusCode(),
                    'body' => $errorBody,
                    'retry_after' => $response->getHeaderLine('retry-after'),
                    'limit_requests' => $response->getHeaderLine(
                        'x-ratelimit-limit-requests'
                    ),
                    'remaining_requests' => $response->getHeaderLine(
                        'x-ratelimit-remaining-requests'
                    ),
                    'limit_tokens' => $response->getHeaderLine(
                        'x-ratelimit-limit-tokens'
                    ),
                    'remaining_tokens' => $response->getHeaderLine(
                        'x-ratelimit-remaining-tokens'
                    ),
                ]);
            }

            return $response;
        }
    );
}
    }
}
