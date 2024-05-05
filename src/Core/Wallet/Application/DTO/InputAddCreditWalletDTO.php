<?php

namespace Core\Wallet\Application\DTO;

use Core\SeedWork\Domain\ValueObjects\Uuid;

class InputAddCreditWalletDTO
{
    public function __construct(
        private float $amount,
        public Uuid $userId,
    ) {}

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

}