<?php

namespace Core\Wallet\Application\DTO;

use Core\SeedWork\Domain\ValueObjects\Uuid;

class InputAddCreditWalletDTO
{
    public function __construct(
        private float $amount,
        private string $cpf
    ) {}

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }
}