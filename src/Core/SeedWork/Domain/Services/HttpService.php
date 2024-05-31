<?php

namespace Core\SeedWork\Domain\Services;

class HttpService implements HttpServiceInterface
{
    public function get(string $url, array $headers = []): array
    {
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        $response = curl_exec($ch);
    
        if ($response === false) {
            throw new \Exception('Failed to fetch URL: ' . $url . ', Error: ' . curl_error($ch));
        }
    
        curl_close($ch);
    
        $decodedResponse = json_decode($response, true);
    
        if ($decodedResponse === null) {
            throw new \Exception('Failed to decode response from URL: ' . $url);
        }
    
        return $decodedResponse;
    }
}
