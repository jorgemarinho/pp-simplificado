<?php

namespace Core\User\Domain\Entities;

use Core\SeedWork\Domain\Entities\Entity;
use Core\SeedWork\Domain\Validation\DomainValidation;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use DateTime;

class Company extends Entity
{

    public function __construct(
        protected string $cnpj,
        protected Uuid $peopleId,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,

    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function getCnpj(): string
    {
        return $this->cnpj;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'cnpj' => $this->cnpj,
            'people_id' => (string)$this->peopleId,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    private function validate()
    {
        DomainValidation::isCnpj($this->cnpj);
        DomainValidation::strMinLength($this->cnpj, 14);
    }
}
