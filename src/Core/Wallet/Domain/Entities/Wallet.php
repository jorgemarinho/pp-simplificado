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

    public function getUserId(): Uuid|string
    {
        return (string)$this->userId;
    }

    public function deposit(float $value)
    {
        $this->balance += $value;
        $this->validate();
    }

    public function withdraw(float $value)
    {
        $this->balance -= $value;
        $this->validate();
    }
    
    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'balance' => $this->balance,
            'user_id' => (string) $this->userId,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'update_at' => $this->updateAt?->format('Y-m-d H:i:s'),
        ];
    }

    private function validate()
    {
        DomainValidation::validateNonNegative($this->balance);
    }
}