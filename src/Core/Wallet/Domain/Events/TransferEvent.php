<?php

namespace Core\Wallet\Domain\Events;

use Core\SeedWork\Domain\Events\EventInterface;
use Core\User\Domain\Entities\User;

class TransferEvent implements EventInterface
{
    public function __construct(
        private User $payerUser,
        private User $payeeUser,
        private float $value
    ) {
    }

    public function getEventName(): string
    {
        return 'wallet.transfer';
    }

    public function getPayload(): array
    {
        return [
            'payer_user' => [
                'value' =>  $this->value,
                'email' => $this->payerUser->getEmail(),
                'phone' => $this->payerUser?->getPeople()?->getPhone()
            ],
            'payee_user' => [
                'value' =>  $this->value,
                'email' => $this->payeeUser->getEmail(),
                'phone' => $this->payeeUser?->getPeople()?->getPhone()
            ]
        ];
    }
}
