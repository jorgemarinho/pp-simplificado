<?php


namespace Core\User\Application\UseCase;

use Core\User\Application\DTO\InputListUserDTO;
use Core\User\Application\DTO\OutputListUserDTO;
use Core\User\Domain\Repository\UserRepositoryInterface;

class ListUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {}

    public function execute(InputListUserDTO $inputListUserDTO): OutputListUserDTO
    {
        $users = $this->userRepository->paginate(
            filter: $inputListUserDTO->filter,
            order: $inputListUserDTO->order,
            page: $inputListUserDTO->page,
            totalPage: $inputListUserDTO->totalPage,
        );

        return new OutputListUserDTO(
            items: $users->items(),
            total: $users->total(),
            current_page: $users->currentPage(),
            last_page: $users->lastPage(),
            first_page: $users->firstPage(),
            per_page: $users->perPage(),
            to: $users->to(),
            from: $users->from(),

        );
    }
}
