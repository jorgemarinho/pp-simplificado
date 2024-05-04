<?php

namespace Core\User\Domain\Repository;

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function insert(User $user): User;

    public function findByEmail(string $email): ?User;

    public function findById(Uuid $id): ?User;

    public function checkUserCredentials(string $email, string $password): bool;

}