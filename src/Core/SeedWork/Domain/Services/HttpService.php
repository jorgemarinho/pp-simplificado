<?php

namespace Core\SeedWork\Domain\Services;

class HttpService implements HttpServiceInterface
{
    public function get(string $url, array $headers = []): array
    {
        $response = file_get_contents($url);

        if ($response === false) {
            throw new \Exception('Failed to fetch URL: ' . $url);
        }

        $decodedResponse = json_decode($response, true);

        if ($decodedResponse === null) {
            throw new \Exception('Failed to decode response from URL: ' . $url);
        }

        return $decodedResponse;
    }
}
