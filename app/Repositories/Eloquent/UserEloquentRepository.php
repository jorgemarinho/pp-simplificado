<?php


namespace App\Repositories\Eloquent;

use App\Models\User as Model;
use Core\User\Domain\Entities\User;
use Core\User\Domain\Repository\UserRepositoryInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid as ValueObjectUuid;
use Core\SeedWork\Domain\Exception\NotFoundException;
use Core\User\Domain\Entities\People;
use Core\User\Services\PasswordHasher;


class UserEloquentRepository implements UserRepositoryInterface
{

    private PasswordHasher $passwordHasher;

    public function __construct(private Model $model)
    {
        $this->passwordHasher = new PasswordHasher();
    }

    public function insert(User $user): User
    {
        $dataDB = $this->model->create([
            'id' => $user->id(),
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
        return $this->model->where('email', $email)->where('password', $this->passwordHasher->hashPassword($password))->exists();
    }

    private function convertToEntity(Model $model): User
    {
        return new User(
            id: new ValueObjectUuid($model->id),
            email: $model->email,
            password: $model->password,
            userType: $model->user_type_id,
            createdAt: $model->created_at,
            people: $this->convertToPeople($model)
        );
    }

    private function convertToPeople(Model $model): People 
    {
        return new People(
            fullName: $model->people->full_name,
            cpf: $model->people->cpf,
            phone: $model->people->phone,
            userId: new ValueObjectUuid($model->id),
            id: new ValueObjectUuid($model->people->id),
            createdAt: $model->people->created_at
        );
    }
}
