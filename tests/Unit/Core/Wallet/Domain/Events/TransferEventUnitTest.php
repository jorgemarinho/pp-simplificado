<?php

use Core\Wallet\Domain\Events\TransferEvent;

it('can create a transfer event', function () {
  
    
    $amount = 100.00;
    
    // Act
    $sourceAccountId = new Core\User\Domain\Entities\User("jorgeluizbsi@gmail.com", null);
    $destinationAccountId = new Core\User\Domain\Entities\User("teste@gmail.com", null);

    $event = new TransferEvent($sourceAccountId, $destinationAccountId, $amount);
    
    // Assert
    expect($event->getPayload()['payer_user']['email'])->toBe($sourceAccountId->getEmail());
    expect($event->getPayload()['payee_user']['email'])->toBe($destinationAccountId->getEmail());
    expect($event->getPayload()['payee_user']['value'])->toBe($amount);
});

