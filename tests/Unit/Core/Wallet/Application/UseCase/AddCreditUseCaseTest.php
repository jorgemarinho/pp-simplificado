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

    $walletRepository->method('findWalletByCpf')
        ->willReturn($wallet);

    $walletRepository->method('update')
        ->willReturn(true);

    $inputAddCreditWalletDTO = new InputAddCreditWalletDTO(100, '12345678901');
    $addCreditUseCase = new AddCreditUseCase($walletRepository, $logger, $transaction);
    $outputAddCreditWalletDTO = $addCreditUseCase->execute($inputAddCreditWalletDTO);

    expect($outputAddCreditWalletDTO->isSuccess())->toBeTrue();
    expect($outputAddCreditWalletDTO->getBalance())->toBe(100.0);
    expect($outputAddCreditWalletDTO->getMessage())->toBe([AddCreditUseCase::MESSAGE_SUCCESS]);
});