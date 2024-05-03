<?php

namespace Core\User\Application\DTO;

use Core\SeedWork\Domain\Notification\Notification;
use Core\User\Domain\Entities\People;
use Core\User\Domain\Entities\User;

class OutputUserDTO
{
    public function __construct(
        private bool $success,
        private array $message,
        private ?User $user = null,
        private ?People $people = null
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getPeople(): ?People
    {
        return $this->people;
    }

}
