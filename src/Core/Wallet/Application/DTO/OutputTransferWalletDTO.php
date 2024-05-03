<?php

namespace Core\Wallet\Application\DTO;

class OutputTransferWalletDTO
{
    public function __construct(
        private bool $success,
        private array $message,
        private ?float $balance = null
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }
}