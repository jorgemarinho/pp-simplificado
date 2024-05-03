<?php

namespace Core\Wallet\Application\DTO;

use Core\SeedWork\Domain\Notification\Notification;
use Core\SeedWork\Domain\ValueObjects\Uuid;

class InputTransferWalletDTO
{
    public function __construct(
        public Uuid $payerUserId,
        public Uuid $payeeUserId,
        public float $value,
        public string $passwordPayerUserId
    ) {
    }

    public function isValid(Notification $notification): bool
    {
        if ($this->value <= 0) {
            $notification->addError('The value must be greater than 0');
            return false;
        }

        if (empty($this->passwordPayerUserId)) {
            $notification->addError('Password is required');
            return false;
        }

        return true;
    }

}