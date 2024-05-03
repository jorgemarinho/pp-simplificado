<?php

namespace Core\SeedWork\Domain\Repository;

use Core\SeedWork\Domain\Entities\Entity;

interface EntityRepositoryInterface
{
    public function insert(Entity $entity): Entity;

    public function findById(string $entityId): Entity;

    public function findAll(string $filter = '', $order = 'DESC'): array;

    public function update(Entity $entity): Entity;

    public function delete(string $entityId): bool;
}