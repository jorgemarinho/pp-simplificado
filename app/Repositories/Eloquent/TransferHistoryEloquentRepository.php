<?php

namespace App\Repositories\Eloquent;

use App\Models\TransferHistory as Model;
use Core\Wallet\Domain\Entities\TransferHistory;
use Core\Wallet\Domain\Repository\TransferHistoryRepositoryInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid as ValueObjectUuid;

class TransferHistoryEloquentRepository implements TransferHistoryRepositoryInterface   
{

    public function __construct(private Model $model)
    {
    }

    public function insert(TransferHistory $transferHistory): TransferHistory
    {
        $dataDB = $this->model->create([
            'id' => $transferHistory->id(),
            'amount' => $transferHistory->getAmount(),
            'payee_user_id' => $transferHistory->getPayeeUserId(),
            'payer_user_id' => $transferHistory->getPayerUserId(),
        ]);

        return $this->convertToEntity($dataDB);
    }

    private function convertToEntity(Model $model): TransferHistory
    {
        return new TransferHistory(
            amount: $model->amount,
            payeeUserId: new ValueObjectUuid($model->payee_user_id),
            payerUserId: new ValueObjectUuid($model->payer_user_id),
            id: new ValueObjectUuid($model->id)
        );
    }
}