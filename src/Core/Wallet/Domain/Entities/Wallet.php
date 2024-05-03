<?php

namespace Core\Wallet\Domain\Entities;

use Core\SeedWork\Domain\Entities\Entity;
use Core\SeedWork\Domain\Validation\DomainValidation;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use DateTime;

class Wallet extends Entity
{
    public function __construct(
        protected float $balance,
        protected Uuid $userId,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,
        protected ?DateTime $updateAt = null,
    ) {

        $this->updateAt = $this->id ? new DateTime() : null;
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function deposit(float $value)
    {
        $this->balance += $value;
    }

    public function withdraw(float $value)
    {
        $this->balance -= $value;
    }
    
    private function validate()
    {
        DomainValidation::notNull($this->balance);
    }
}