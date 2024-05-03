<?php

namespace Core\Wallet\Domain\Repository;

use Core\SeedWork\Domain\ValueObjects\Uuid;

interface TransferHistoryRepositoryInterface
{
    public function insert(Uuid $payerUserId,Uuid $payeeUserId, $amount): bool;
}