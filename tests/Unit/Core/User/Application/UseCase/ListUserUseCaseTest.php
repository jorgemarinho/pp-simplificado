<?php

use Core\SeedWork\Domain\Repository\PaginationInterface;
use Core\User\Application\DTO\InputListUserDTO;
use Core\User\Application\UseCase\ListUserUseCase;
use Core\User\Domain\Repository\UserRepositoryInterface;
use Mockery\MockInterface;

function mockPagination(array $items = [])
{
    $mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
    $mockPagination->shouldReceive('items')->andReturn($items);
    $mockPagination->shouldReceive('total')->andReturn(0);
    $mockPagination->shouldReceive('currentPage')->andReturn(0);
    $mockPagination->shouldReceive('firstPage')->andReturn(0);
    $mockPagination->shouldReceive('lastPage')->andReturn(0);
    $mockPagination->shouldReceive('perPage')->andReturn(0);
    $mockPagination->shouldReceive('to')->andReturn(0);
    $mockPagination->shouldReceive('from')->andReturn(0);

    return $mockPagination;
}

it('should return a list of users', function () {

    $register = new stdClass();
    $register->id = 'id';
    $register->name = 'name';
    $register->description = 'description';
    $register->is_active = 'is_active';
    $register->created_at = 'created_at';
    $register->updated_at = 'created_at';
    $register->deleted_at = 'created_at';

    $mockPagination = mockPagination([
        $register,
    ]);

    $userRepository = mock(UserRepositoryInterface::class, function (MockInterface $mock) use ($mockPagination) {
        $mock->shouldReceive('paginate')->andReturn($mockPagination);
    });

    $inputListUserDTO = Mockery::mock(InputListUserDTO::class, ['filter', 'desc']);

    $useCase = new ListUserUseCase($userRepository);

    $responseUseCase = $useCase->execute($inputListUserDTO);

    $this->assertEquals($responseUseCase->items[0]->id, 'id');
    $this->assertEquals($responseUseCase->items[0]->name, 'name');
    $this->assertEquals($responseUseCase->items[0]->description, 'description');
    $this->assertEquals($responseUseCase->items[0]->is_active, 'is_active');
    $this->assertEquals($responseUseCase->items[0]->created_at, 'created_at');
    $this->assertEquals($responseUseCase->items[0]->updated_at, 'created_at');
    $this->assertEquals($responseUseCase->items[0]->deleted_at, 'created_at');
});

it('should return a empty list of users', function () {

    $mockPagination = mockPagination();

    $userRepository = mock(UserRepositoryInterface::class, function (MockInterface $mock) use ($mockPagination) {
        $mock->shouldReceive('paginate')->andReturn($mockPagination);
    });

    $inputListUserDTO = Mockery::mock(InputListUserDTO::class, ['filter', 'desc']);

    $useCase = new ListUserUseCase($userRepository);

    $responseUseCase = $useCase->execute($inputListUserDTO);

    $this->assertEquals($responseUseCase->items, []);
});