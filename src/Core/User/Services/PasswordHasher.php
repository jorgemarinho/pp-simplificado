<?php

namespace Core\User\Services;

class PasswordHasher
{
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $senhaEmTextoPlano, string $senhaBancoHash): bool {
        return password_verify($senhaEmTextoPlano, $senhaBancoHash);
    }
}