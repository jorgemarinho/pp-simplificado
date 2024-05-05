<?php

namespace App\Repositories\Eloquent;

use Core\Wallet\Domain\Repository\WalletRepositoryInterface;
use App\Models\Wallet as Model;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\Wallet\Domain\Entities\Wallet;
use Core\SeedWork\Domain\ValueObjects\Uuid as ValueObjectUuid;


class WalletEloquentRepository implements WalletRepositoryInterface
{

    public function __construct(private Model $model)
    {
    }

    public function insert(Wallet $wallet): Wallet
    {
        $dataDB = $this->model->create([
            'id' => $wallet->id(),
            'user_id' => $wallet->getUserId(),
            'balance' => $wallet->getBalance(),
        ]);

        return $this->convertToEntity($dataDB);
    }

    public function update(Wallet $wallet): bool
    {
        return $this->model->where('id', $wallet->id())->update([
            'balance' => $wallet->getBalance(),
        ]);
    }

    public function findWalletByUserId(Uuid $userId): Wallet
    {
        $dataDB = $this->model->where('user_id', $userId)->first();

        return $this->convertToEntity($dataDB);
    }

    public function findWalletByCpf(string $cpf): Wallet
    {
        $dataDB = $this->model->whereHas('user.people', function ($query) use ($cpf) {
            $query->where('cpf', $cpf);
        })->first();

        return $this->convertToEntity($dataDB);
    }

    private function convertToEntity(Model $model): Wallet
    {
        return new Wallet(
            balance: $model->balance,
            userId: new ValueObjectUuid($model->user_id),
            id:  new ValueObjectUuid($model->id)
        );
    }
}
