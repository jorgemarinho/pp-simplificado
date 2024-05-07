<?php

namespace Core\User\Application\UseCase;

use Core\User\Application\DTO\InputUserDTO;
use Core\User\Domain\Repository\UserRepositoryInterface;

class CheckUserCredentialUseCase
{
    public function __construct(private UserRepositoryInterface $userRepositoryInterface)
    {
    }

    public function execute(InputUserDTO $inputCheckUserCredentialDTO): bool
    {
        if ($this->userRepositoryInterface->checkUserCredentials($inputCheckUserCredentialDTO->email, $inputCheckUserCredentialDTO->password)) {
            return true;
        }

        return false;
    }
}
