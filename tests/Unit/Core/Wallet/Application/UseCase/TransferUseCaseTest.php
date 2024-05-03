<?php

use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\SeedWork\Domain\Enum\UserType;
use Core\SeedWork\Domain\Services\HttpServiceInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Domain\Entities\User;
use Core\User\Domain\Repository\UserUseCaseRepositoryInterface;
use Core\Wallet\Application\DTO\InputTransferWalletDTO;
use Core\Wallet\Application\UseCase\TransferUseCase;
use Core\Wallet\Domain\Entities\Wallet;
use Core\Wallet\Domain\Repository\TransferHistoryRepositoryInterface;
use Core\Wallet\Domain\Repository\TransferWalletRepositoryInterface;
use Core\Wallet\Interfaces\TransferEventManagerInterface;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;

it('can transfer amount from one wallet to another', function () {

    $amount = 100.0;
    $sourceWalletAmount = 200.0;
    $destinationWalletAmount = 50.0;

    $payerUserId = Uuid::random();
    $payeeUserId = Uuid::random();
    $passwordPayerUserId = '12345678';

    $sourceWallet = new Wallet($sourceWalletAmount, $payerUserId);
    $destinationWallet = new Wallet($destinationWalletAmount, $payeeUserId);

    $TransferWalletRepositoryInterface = mock(TransferWalletRepositoryInterface::class, function (MockInterface $mock) use ($payerUserId, $payeeUserId, $sourceWallet, $destinationWallet) {
        $mock->shouldReceive('findWalletByUserId')->with($payerUserId)->once()->andReturn($sourceWallet);
        $mock->shouldReceive('findWalletByUserId')->with($payeeUserId)->once()->andReturn($destinationWallet);
        $mock->shouldReceive('update')->twice();
    });

    $TransferHistoryRepositoryInterface = mock(TransferHistoryRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->once();
    });

    $UserUseCaseRepositoryInterface = mock(UserUseCaseRepositoryInterface::class, function (MockInterface $mock) use ($payerUserId, $payeeUserId) {
        $mock->shouldReceive('findById')->with($payerUserId)->once()->andReturn(new User("jorgeluizbsi@gmail.com", '12345678' , 1 , $payerUserId));
        $mock->shouldReceive('findById')->with($payeeUserId)->once()->andReturn(new User( "pedro@gmail.com", null, 2 , $payeeUserId));
        $mock->shouldReceive('checkUserCredentials')->with("jorgeluizbsi@gmail.com", '12345678')->andReturn(true);
    });

    $transferEventManagerInterface = mock(TransferEventManagerInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('dispatch')->once();
    });

    $httpService = mock(HttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('get')->once()->andReturn(
            json_encode(["message" => "Autorizado"])
        );
        $mock->shouldReceive('get')->once()->andReturn(
            json_encode(["message" => true])
        );
    });

    $logger = mock(LoggerInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('error')->never();
    });

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->once();
        $mock->shouldReceive('commit')->once();
        $mock->shouldReceive('rollBack')->never();
    });

    $transferUseCase = new TransferUseCase(
        $TransferWalletRepositoryInterface,
        $TransferHistoryRepositoryInterface,
        $UserUseCaseRepositoryInterface,
        $transferEventManagerInterface,
        $httpService,
        $logger,
        $transaction
    );

    $outputTransferWalletDTO = $transferUseCase->execute(new InputTransferWalletDTO(
        payerUserId: $payerUserId,
        payeeUserId: $payeeUserId,
        value: $amount,
        passwordPayerUserId: $passwordPayerUserId
    ));

    expect($outputTransferWalletDTO->isSuccess())->toBe(true);
    expect($outputTransferWalletDTO->getMessage())->toBe([TransferUseCase::MESSAGE_SUCCESS]);
    expect($sourceWallet->getBalance())->toBe($sourceWalletAmount - $amount);
    expect($destinationWallet->getBalance())->toBe($destinationWalletAmount + $amount);

});

it('can not transfer amount when payer user for merchant', function () {

    $amount = 100.0;
    $sourceWalletAmount = 200.0;
    $destinationWalletAmount = 50.0;

    $payerUserId = Uuid::random();
    $payeeUserId = Uuid::random();
    $passwordPayerUserId = '12345678';

    $sourceWallet = new Wallet($sourceWalletAmount, $payerUserId);
    $destinationWallet = new Wallet($destinationWalletAmount, $payeeUserId);

    $TransferWalletRepositoryInterface = mock(TransferWalletRepositoryInterface::class, function (MockInterface $mock) use ($payerUserId, $payeeUserId, $sourceWallet, $destinationWallet) {
        $mock->shouldReceive('findWalletByUserId')->with($payerUserId)->once()->andReturn($sourceWallet);
        $mock->shouldReceive('findWalletByUserId')->with($payeeUserId)->once()->andReturn($destinationWallet);
        $mock->shouldReceive('update')->never();
    });

    $TransferHistoryRepositoryInterface = mock(TransferHistoryRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
    });

    $UserUseCaseRepositoryInterface = mock(UserUseCaseRepositoryInterface::class, function (MockInterface $mock) use ($payerUserId, $payeeUserId) {
        $mock->shouldReceive('findById')->with($payerUserId)->once()->andReturn(new User("jorgeluizbsi@gmail.com", '12345678' , "2" , $payerUserId));
        $mock->shouldReceive('findById')->with($payeeUserId)->once()->andReturn(new User( "pedro@gmail.com", null,"2" , $payeeUserId));
        $mock->shouldReceive('checkUserCredentials')->with("jorgeluizbsi@gmail.com", '12345678')->andReturn(true);
    });

    $transferEventManagerInterface = mock(TransferEventManagerInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('dispatch')->never();
    });

    $httpService = mock(HttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('get')->once();
    });

    $logger = mock(LoggerInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('error')->never();
    });

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->once();
        $mock->shouldReceive('commit')->never();
        $mock->shouldReceive('rollBack')->never();
    });

    $transferUseCase = new TransferUseCase(
        $TransferWalletRepositoryInterface,
        $TransferHistoryRepositoryInterface,
        $UserUseCaseRepositoryInterface,
        $transferEventManagerInterface,
        $httpService,
        $logger,
        $transaction
    );

    $outputTransferWalletDTO = $transferUseCase->execute(new InputTransferWalletDTO(
        payerUserId: $payerUserId,
        payeeUserId: $payeeUserId,
        value: $amount,
        passwordPayerUserId: $passwordPayerUserId
    ));

    expect($outputTransferWalletDTO->isSuccess())->toBe(false);
    expect($outputTransferWalletDTO->getMessage())->toBe([TransferUseCase::ERROR_MERCHANT_CANNOT_TRANSFER, TransferUseCase::ERROR_UNAUTHORIZED_TRANSFER]); 
});

it('when the payer has no balance', function () {

    $amount = 100.0;
    $sourceWalletAmount = 20.0;
    $destinationWalletAmount = 50.0;

    $payerUserId = Uuid::random();
    $payeeUserId = Uuid::random();
    $passwordPayerUserId = '12345678';

    $sourceWallet = new Wallet($sourceWalletAmount, $payerUserId);
    $destinationWallet = new Wallet($destinationWalletAmount, $payeeUserId);

    $TransferWalletRepositoryInterface = mock(TransferWalletRepositoryInterface::class, function (MockInterface $mock) use ($payerUserId, $payeeUserId, $sourceWallet, $destinationWallet) {
        $mock->shouldReceive('findWalletByUserId')->with($payerUserId)->once()->andReturn($sourceWallet);
        $mock->shouldReceive('findWalletByUserId')->with($payeeUserId)->once()->andReturn($destinationWallet);
        $mock->shouldReceive('update')->never();
    });

    $TransferHistoryRepositoryInterface = mock(TransferHistoryRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('insert')->never();
    });

    $UserUseCaseRepositoryInterface = mock(UserUseCaseRepositoryInterface::class, function (MockInterface $mock) use ($payerUserId, $payeeUserId) {
        $mock->shouldReceive('findById')->with($payerUserId)->once()->andReturn(new User("jorgeluizbsi@gmail.com", '12345678' , "1" , $payerUserId));
        $mock->shouldReceive('findById')->with($payeeUserId)->once()->andReturn(new User( "pedro@gmail.com", null,"2" , $payeeUserId));
        $mock->shouldReceive('checkUserCredentials')->with("jorgeluizbsi@gmail.com", '12345678')->andReturn(true);
    });

    $transferEventManagerInterface = mock(TransferEventManagerInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('dispatch')->never();
    });

    $httpService = mock(HttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('get')->once();
    });

    $logger = mock(LoggerInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('error')->never();
    });

    $transaction = mock(TransactionInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('begin')->once();
        $mock->shouldReceive('commit')->never();
        $mock->shouldReceive('rollBack')->never();
    });

    $transferUseCase = new TransferUseCase(
        $TransferWalletRepositoryInterface,
        $TransferHistoryRepositoryInterface,
        $UserUseCaseRepositoryInterface,
        $transferEventManagerInterface,
        $httpService,
        $logger,
        $transaction
    );

    $outputTransferWalletDTO = $transferUseCase->execute(new InputTransferWalletDTO(
        payerUserId: $payerUserId,
        payeeUserId: $payeeUserId,
        value: $amount,
        passwordPayerUserId: $passwordPayerUserId
    ));

    expect($outputTransferWalletDTO->isSuccess())->toBe(false);
    expect($outputTransferWalletDTO->getMessage())->toBe([TransferUseCase::ERROR_INSUFFICIENT_BALANCE, TransferUseCase::ERROR_UNAUTHORIZED_TRANSFER]);
});       

