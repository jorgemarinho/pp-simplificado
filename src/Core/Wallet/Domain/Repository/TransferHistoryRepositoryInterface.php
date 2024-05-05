<?php

namespace Core\Wallet\Domain\Repository;

use Core\Wallet\Domain\Entities\TransferHistory;

interface TransferHistoryRepositoryInterface
{
    public function insert(TransferHistory $transferHistory): TransferHistory;
}