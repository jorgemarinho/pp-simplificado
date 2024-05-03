<?php

namespace Core\Wallet\Domain\Repository;

use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Domain\Entities\User;
use Core\Wallet\Domain\Entities\Wallet;

interface TransferWalletRepositoryInterface
{

    public function findWalletByUserId(Uuid $userId): Wallet;

    public function update(Wallet $wallet): bool;
}