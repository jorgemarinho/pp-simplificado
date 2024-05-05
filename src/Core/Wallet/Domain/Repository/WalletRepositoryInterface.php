<?php

namespace Core\Wallet\Domain\Repository;

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\Wallet\Domain\Entities\Wallet;

interface WalletRepositoryInterface
{
    public function insert(Wallet $wallet): Wallet;

    public function update(Wallet $wallet): bool;

    public function findWalletByUserId(Uuid $userId): ?Wallet;

    public function findWalletByCpf(string $cpf): ?Wallet;

}