<?php

namespace Core\Wallet\Application\UseCase;

use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Core\SeedWork\Domain\Notification\Notification;
use Core\Wallet\Application\DTO\InputAddCreditWalletDTO;
use Core\Wallet\Application\DTO\OutputAddCreditWalletDTO;
use Core\Wallet\Domain\Repository\WalletRepositoryInterface;
use Psr\Log\LoggerInterface;

class AddCreditUseCase
{
    const ERROR_WALLET_NOT_FOUND = 'Wallet not found for the specified user.';
    const MESSAGE_SUCCESS = 'Credit added successfully.';
    const MESSAGE_ERROR = 'An error occurred while adding credit. Please try again.';

    private Notification $notification;

    public function __construct(
        private WalletRepositoryInterface $walletRepository,
        private LoggerInterface $logger,
        private TransactionInterface $transaction
    ) {
        $this->notification = new Notification();
    }

    public function execute(InputAddCreditWalletDTO $inputAddCreditWalletDTO): OutputAddCreditWalletDTO
    {
        try {

            $wallet = $this->walletRepository->findWalletByUserId($inputAddCreditWalletDTO->getUserId());

            if (!$wallet) {
                $this->notification->addError(self::ERROR_WALLET_NOT_FOUND);
            }

            if ($this->notification->hasErrors()) {
                return new OutputAddCreditWalletDTO(false, $this->notification->getErrors(), $inputAddCreditWalletDTO->getAmount());
            }

            $this->transaction->begin();

            $wallet->deposit($inputAddCreditWalletDTO->getAmount());

            $this->walletRepository->update($wallet);

            $this->transaction->commit();

            return new OutputAddCreditWalletDTO(true, [self::MESSAGE_SUCCESS], $inputAddCreditWalletDTO->getAmount());
        } catch (\Exception $e) {
            $this->transaction->rollBack();
            $this->logger->error("AddCreditUseCase " . date('Y-m-d H:i:s') . " CPF : " . $inputAddCreditWalletDTO->getCpf() . " Error: " . $e->getMessage());

            return new OutputAddCreditWalletDTO(false, [self::MESSAGE_ERROR], $inputAddCreditWalletDTO->getAmount());
        }
    }
}
