<?php

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\Wallet\Domain\Entities\Wallet;

it('can create a wallet entity', function() {

    $balance = 100.00;
    $userId =  Uuid::random();

    $wallet = new Wallet($balance, $userId);

    expect($wallet)->toBeInstanceOf(Wallet::class);
    expect($wallet->balance)->toBe($balance);
    expect($wallet->userId)->toBe($userId);
});