<?php

namespace Core\Wallet\Application\DTO;

class OutputAddCreditWalletDTO
{

    public function __construct(
        private bool $success,
        private array $message,
        private float $balance
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getMessage(): array
    {
        return $this->message;
    }
}
