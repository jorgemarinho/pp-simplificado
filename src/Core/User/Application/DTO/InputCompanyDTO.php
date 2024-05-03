<?php

namespace Core\User\Application\DTO;

use Core\SeedWork\Domain\Notification\Notification;

class InputCompanyDTO
{
    public function __construct(
        public string $cnpj)
    {
    }

    public function isValid(Notification $notification): bool
    {
        $isValid = true;
        if (empty($this->cnpj)) {
            $notification->addError('CNPJ is required');
            $isValid = false;
        }
        
        return $isValid;
    }
}