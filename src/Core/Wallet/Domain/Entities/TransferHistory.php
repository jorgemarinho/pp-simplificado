<?php

namespace Core\Wallet\Domain\Entities;

use Core\SeedWork\Domain\Entities\Entity;
use Core\SeedWork\Domain\Validation\DomainValidation;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use DateTime;

class TransferHistory extends Entity
{
    public function __construct(
        protected float $amount,
        protected Uuid $payeeUserId,
        protected Uuid $payerUserId,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPayeeUserId(): Uuid
    {
        return $this->payeeUserId;
    }

    public function getPayerUserId(): Uuid
    {
        return $this->payerUserId;
    }

    private function validate()
    {
        DomainValidation::notNull($this->amount);
    }
}
