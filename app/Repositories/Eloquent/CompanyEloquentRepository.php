<?php

namespace App\Repositories\Eloquent;

use App\Models\Company as Model;
use Core\User\Domain\Entities\Company;
use Core\User\Domain\Repository\CompanyRepositoryInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid as ValueObjectUuid;

class CompanyEloquentRepository implements CompanyRepositoryInterface
{
    public function __construct(private Model $model)
    {
    }

    public function insert(Company $company): Company
    {
        $dataDB = $this->model->create([
            'id' => $company->id(),
            'cnpj' => $company->getCnpj(),
            'people_id' => $company->peopleId,
        ]);

        return $this->convertToEntity($dataDB);
    }

    public function findByCnpj(string $cnpj): ?Company
    {
        if (!$dataDb = $this->model->where('cnpj', $cnpj)->first()) {
            return null;
        }

        return $this->convertToEntity($dataDb);
    }

    private function convertToEntity(Model $model): Company
    {
        return new Company(
            cnpj: $model->cnpj,
            peopleId:  new ValueObjectUuid( $model->people->id ),
            id: new ValueObjectUuid($model->id),
        );
    }
}
