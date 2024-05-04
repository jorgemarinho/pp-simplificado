<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Core\User\Application\DTO\InputCompanyDTO;
use Core\User\Application\DTO\InputPeopleDTO;
use Core\User\Application\DTO\InputUserDTO;
use Core\User\Application\UseCase\CreateUserUseCase;

class UserController extends Controller
{
    
    /**
     * Gravação de um novo usuário.
     *
     * @param UserRequest $request oobjeto de request.
     * @param CreateUserUseCase $createUserUseCase o caso de uso de criação de usuário.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request, CreateUserUseCase $createUserUseCase)
    {
        $inputCompanyDTO =  $request->cnpj ? new InputCompanyDTO(
            cnpj: $request->cnpj,
        ) : null;

        $response = $createUserUseCase->execute(
            userDTO: new InputUserDTO(
                email: $request->email,
                password: $request->password
            ),
            peopleDTO: new InputPeopleDTO(
                fullName: $request->full_name,
                cpf: $request->cpf,
                phone: $request->phone
            ),
            companyDTO:  $inputCompanyDTO
        );

        $httpCode = $response->isSuccess() ? 201 : 400;

        return (new UserResource($response))
            ->response()
            ->setStatusCode($httpCode);
    }
}
