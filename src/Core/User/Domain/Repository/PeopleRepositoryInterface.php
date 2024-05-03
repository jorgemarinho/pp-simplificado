<?php

namespace Core\User\Domain\Repository;

use Core\User\Domain\Entities\People;

interface PeopleRepositoryInterface
{
    public function insert(People $people): People;

    public function findByCpf(string $cpf): ?People;
}