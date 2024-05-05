<?php

use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\Wallet\Application\DTO\InputAddCreditWalletDTO;
use Core\Wallet\Application\UseCase\AddCreditUseCase;
use Core\Wallet\Domain\Entities\Wallet;
use Core\Wallet\Domain\Repository\WalletRepositoryInterface;
use Psr\Log\LoggerInterface;

it('can add credit to the wallet', function () {

    $walletRepository = $this->createMock(WalletRepositoryInterface::class);
    $logger = $this->createMock(LoggerInterface::class);
    $transaction = $this->createMock(TransactionInterface::class);

    $userId = Uuid::random();
    $wallet = new Wallet(0,  $userId);

    $walletRepository->method('findWalletByUserId')
        ->willReturn($wallet);

    $walletRepository->method('update')
        ->willReturn(true);

    $inputAddCreditWalletDTO = new InputAddCreditWalletDTO(100,  $userId);
    $addCreditUseCase = new AddCreditUseCase($walletRepository, $logger, $transaction);
    $outputAddCreditWalletDTO = $addCreditUseCase->execute($inputAddCreditWalletDTO);

    expect($outputAddCreditWalletDTO->isSuccess())->toBeTrue();
    expect($outputAddCreditWalletDTO->getBalance())->toBe(100.0);
    expect($outputAddCreditWalletDTO->getMessage())->toBe([AddCreditUseCase::MESSAGE_SUCCESS]);
});

it('can not add credit to the wallet when the wallet is not found', function () {

    $walletRepository = $this->createMock(WalletRepositoryInterface::class);
    $logger = $this->createMock(LoggerInterface::class);
    $transaction = $this->createMock(TransactionInterface::class);

    $userId = Uuid::random();

    $walletRepository->method('findWalletByUserId')
        ->willReturn(null);

    $inputAddCreditWalletDTO = new InputAddCreditWalletDTO(100,  $userId);
    $addCreditUseCase = new AddCreditUseCase($walletRepository, $logger, $transaction);
    $outputAddCreditWalletDTO = $addCreditUseCase->execute($inputAddCreditWalletDTO);

    expect($outputAddCreditWalletDTO->isSuccess())->toBeFalse();
    expect($outputAddCreditWalletDTO->getBalance())->toBe(100.0);
    expect($outputAddCreditWalletDTO->getMessage())->toBe([AddCreditUseCase::ERROR_WALLET_NOT_FOUND]);
});
