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

    private function validate()
    {
        DomainValidation::isCnpj($this->cnpj);
        DomainValidation::strMinLength($this->cnpj, 14);
    }
}
