<?php

namespace App\Services;

use Core\SeedWork\Domain\Services\HttpServiceInterface;
use Illuminate\Support\Facades\Http;

class HttpService implements HttpServiceInterface
{
    public function get(string $url, array $headers = []): array
    {
        $response = Http::withHeaders($headers)->get($url);

        return $response->json();
    }


}