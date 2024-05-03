<?php

namespace Core\User\Application\DTO;

use Core\SeedWork\Domain\Notification\Notification;

class InputUserDTO
{
    public function __construct(
        public string $email, 
        public string $password)
    {
    }
    
    public function isValid(Notification $notification): bool
    {
        $isValid = true;
        
        if (empty($this->email)) {
            $notification->addError('Email is required');
            $isValid = false;
        }

        if (empty($this->password)) {
            $notification->addError('Password is required');
            $isValid = false;
        } elseif (strlen($this->password) < 8) {
            $notification->addError('Password must be at least 8 characters long');
            $isValid = false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $notification->addError('Invalid email format');
            $isValid = false;
        }
        
        return $isValid;
    }
}
