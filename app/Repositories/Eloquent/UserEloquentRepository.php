<?php


namespace App\Repositories\Eloquent;

use App\Models\User as Model;
use Core\User\Domain\Entities\User;
use Core\User\Domain\Repository\UserUseCaseRepositoryInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid as ValueObjectUuid;
use Core\SeedWork\Domain\Exception\NotFoundException;
class UserUseCaseRepository implements UserUseCaseRepositoryInterface
{

    public function __construct(private Model $model)
    {
    }

    public function insert(User $user): User
    {
        $dataDB = $this->model->create([
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'user_type_id' => $user->getType(),
        ]);

        return $this->convertToEntity($dataDB);
    }

    public function findById(ValueObjectUuid $userId): User
    {
        if (!$dataDb = $this->model->find($userId)) {
            throw new NotFoundException("User {$userId} Not Found");
        }

        return $this->convertToEntity($dataDb);
    }

    public function findByEmail(string $email): ?User
    {
        if (!$dataDb = $this->model->where('email', $email)->first()) {
            return null;
        }

        return $this->convertToEntity($dataDb);
    }

    public function checkUserCredentials(string $email, string $password): bool
    {
        return $this->model->where('email', $email)->where('password', $password)->exists();
    }

    private function convertToEntity(Model $model): User
    {
        return new User(
            id: new ValueObjectUuid($model->id),
            email: $model->email,
            password: $model->password,
            userType: $model->user_type_id,
            createdAt: $model->created_at
        );
    }
}
