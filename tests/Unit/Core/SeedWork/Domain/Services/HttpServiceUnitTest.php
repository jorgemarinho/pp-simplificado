<?php

use Core\SeedWork\Domain\Services\HttpService;
use Illuminate\Support\Facades\Http;


it('can get a response from a url', function () {
    
    $url = 'https://run.mocky.io/v3/f3737791-a6d0-4cbd-acfd-c0934024d5c0';
    $httpService = new HttpService();

    $response = $httpService->get($url);

    expect($response)->toBeArray();
});