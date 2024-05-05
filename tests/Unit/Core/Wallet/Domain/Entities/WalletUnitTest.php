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

it('can add credit to wallet', function() {

    $balance = 100.00;
    $userId =  Uuid::random();
    $amount = 50.00;

    $wallet = new Wallet($balance, $userId);
    $wallet->deposit($amount);

    expect($wallet->balance)->toBe(150.00);
});

it('can remove credit from wallet', function() {

    $balance = 100.00;
    $userId =  Uuid::random();
    $amount = 50.00;

    $wallet = new Wallet($balance, $userId);
    $wallet->withdraw($amount);

    expect($wallet->balance)->toBe(50.00);
});

it('can convert wallet entity to array', function() {

    $balance = 100.00;
    $userId =  Uuid::random();

    $wallet = new Wallet($balance, $userId);
    $walletArray = $wallet->toArray();

    expect($walletArray)->toBeArray();
    expect($walletArray['balance'])->toBe($balance);
    expect($walletArray['user_id'])->toBe((string)$userId);
    expect($walletArray['id'])->toBe((string)$wallet->id);
    expect($walletArray['created_at'])->toBe($wallet->createdAt->format('Y-m-d H:i:s'));
    expect($walletArray['update_at'])->toBe(null);
});
