<?php

namespace App\Repositories\Eloquent;

use App\Models\People as Model;
use Core\User\Domain\Entities\People;
use Core\User\Domain\Repository\PeopleRepositoryInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid as ValueObjectUuid;

class PeopleEloquentRepository implements PeopleRepositoryInterface
{

    public function __construct(private Model $model)
    {
    }

    public function insert(People $people): People
    {
        $dataDB = $this->model->create([
            'fullName' => $people->getFullName(),
            'cpf' => $people->getCpf(),
            'phone' => $people->getPhone(),
            'user_id' => $people->userId,
        ]);

        return $this->convertToEntity($dataDB);
    }

    public function findByCpf(string $cpf): ?People
    {
        if (!$dataDb = $this->model->where('cpf', $cpf)->first()) {
            return null;
        }

        return $this->convertToEntity($dataDb);
    }

    private function convertToEntity(Model $model): People
    {
        return new People(
            fullName: $model->full_name,
            cpf: $model->cpf,
            phone: $model->phone,
            userId: $model->user_id,
            id: new ValueObjectUuid($model->id),
        );
    }
}
