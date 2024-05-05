<?php

namespace Core\Wallet\Application\DTO;

use Core\SeedWork\Domain\Notification\Notification;
use Core\SeedWork\Domain\ValueObjects\Uuid;

class InputTransferWalletDTO
{
    public function __construct(
        public Uuid $payerUserId,
        public Uuid $payeeUserId,
        public float $value
    ) {
    }

    public function isValid(Notification $notification): bool
    {
        if ($this->value <= 0) {
            $notification->addError('The value must be greater than 0');
            return false;
        }

        return true;
    }

}