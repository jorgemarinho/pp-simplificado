<?php
namespace Core\User\Domain\Entities;

use Core\SeedWork\Domain\Entities\Entity;
use Core\SeedWork\Domain\Enum\UserType;
use Core\SeedWork\Domain\Validation\DomainValidation;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use DateTime;

class User extends Entity
{
    
    public function __construct(
        protected string $email,
        protected ?string $password,
        protected ?int $userType = UserType::CLIENT->value,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,
        protected ?People $people = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }
    
    public function getType(): int
    {
        return $this->userType;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPeople(): ?People
    {
        return $this->people;
    }
    
    private function validate()
    {
        DomainValidation::notNull($this->email);
        DomainValidation::isEmail($this->email);

        if( !empty($this->password) ) {
            DomainValidation::strMinLength($this->password, 6);
        }
    }
}