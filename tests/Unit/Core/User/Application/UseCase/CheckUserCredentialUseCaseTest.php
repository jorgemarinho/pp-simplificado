<?php

use Core\User\Application\DTO\InputUserDTO;
use Core\User\Application\UseCase\CheckUserCredentialUseCase;
use Core\User\Domain\Repository\UserRepositoryInterface;
use Mockery\MockInterface;

it('can check user credentials', function () {

    $UserRepositoryInterface = mock(UserRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('checkUserCredentials')->with("jorgeluizbsi@gmail.com", '12345678')->andReturn(true);
    });

    $email = "jorgeluizbsi@gmail.com";
    $password = "12345678";

    $userDTO = new InputUserDTO($email, $password);

    $useCase = new CheckUserCredentialUseCase($UserRepositoryInterface);

    $result = $useCase->execute($userDTO);

    expect($result)->toBe(true);
});