<?php

namespace App\Repositories\Transaction;

use Core\SeedWork\Application\UseCase\Interfaces\TransactionInterface;
use Illuminate\Support\Facades\DB;

class DBTransaction implements TransactionInterface
{
    public function begin(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}