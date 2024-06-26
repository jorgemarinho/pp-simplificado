<?php

namespace Core\User\Domain\Entities;

use Core\SeedWork\Domain\Entities\Entity;
use Core\SeedWork\Domain\Validation\DomainValidation;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use DateTime;

class People extends Entity
{
    public function __construct(
        protected string $fullName,
        protected string $cpf,
        protected string $phone,
        protected Uuid $userId,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,

    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getUserId(): string
    {
        return (string)$this->userId;
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'full_name' => $this->fullName,
            'cpf' => $this->cpf,
            'phone' => $this->phone,
            'user_id' => (string) $this->userId,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    private function validate()
    {
        DomainValidation::strMinLength($this->fullName, 6);
        DomainValidation::isCpf($this->cpf);
        DomainValidation::strMinLength($this->cpf, 11);
    }
}
