<?php

namespace Core\User\Domain\Repository;

use Core\User\Domain\Entities\Company;

interface CompanyRepositoryInterface
{
    public function insert(Company $company): Company;

    public function findByCnpj(string $cnpj): ?Company;
}    