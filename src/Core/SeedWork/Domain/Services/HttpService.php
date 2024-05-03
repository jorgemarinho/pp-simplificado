<?php

namespace Core\SeedWork\Domain\Services;

class HttpService implements HttpServiceInterface
{
    public function get(string $url): string
    {
        $response = file_get_contents($url);

        if ($response === false) {
            throw new \Exception('Failed to fetch URL: ' . $url);
        }

        return $response;
    }
}
