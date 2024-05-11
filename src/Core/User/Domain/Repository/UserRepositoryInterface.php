<?php

namespace Core\User\Domain\Repository;

use Core\SeedWork\Domain\Repository\PaginationInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Core\User\Domain\Entities\User;

 /**
 * @OA\Schema(
 *  schema="UserRepositoryInterface",
 *  title="User Repository Interface",
 * )
 */
interface UserRepositoryInterface
{
    public function insert(User $user): User;

    public function update(User $user): User;

    public function findByEmail(string $email): ?User;

    public function findById(Uuid $id): ?User;

    public function checkUserCredentials(string $email, string $password): bool;

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface;

}