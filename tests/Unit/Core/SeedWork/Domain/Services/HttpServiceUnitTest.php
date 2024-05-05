<?php

use Core\SeedWork\Domain\Services\HttpService;
use Illuminate\Support\Facades\Http;


it('can get a response from a url', function () {
    
    $url = 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';
    $httpService = new HttpService();

    $response = $httpService->get($url);

    expect($response)->toBeArray();
});