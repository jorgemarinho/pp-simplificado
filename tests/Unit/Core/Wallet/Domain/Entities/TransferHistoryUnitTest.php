<?php

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\Wallet\Domain\Entities\TransferHistory;

it('can create a transfer history entity', function() {

    $amount = 100.00;
    $payeeUserId = Uuid::random();
    $payerUserId = Uuid::random();

    $transferHistory = new TransferHistory($amount, $payeeUserId, $payerUserId);

    expect($transferHistory)->toBeInstanceOf(TransferHistory::class);
    expect($transferHistory->amount)->toBe($amount);
    expect($transferHistory->payeeUserId)->toBe($payeeUserId);
    expect($transferHistory->payerUserId)->toBe($payerUserId);
});