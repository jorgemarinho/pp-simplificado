<?php

namespace Core\User\Application\DTO;

use Core\SeedWork\Domain\Notification\Notification;
use Core\SeedWork\Domain\ValueObjects\Uuid;

class InputPeopleDTO
{
    public function __construct(
        public string $fullName, 
        public string $cpf, 
        public string $phone)
    {
    }

    public function isValid(Notification $notification): bool
    {
        $isValid = true;
        if (empty($this->cpf)) {
            $notification->addError('CPF is required');
            $isValid = false;
        }

        if (empty($this->fullName)) {
            $notification->addError('FullName is required');
            $isValid = false;
        }

        if (empty($this->phone)) {
            $notification->addError('Phone is required');
            $isValid = false;
        }
            
        return $isValid;
    }
}