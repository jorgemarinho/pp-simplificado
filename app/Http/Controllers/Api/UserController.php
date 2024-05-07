<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserListResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Core\User\Application\DTO\InputCompanyDTO;
use Core\User\Application\DTO\InputListUserDTO;
use Core\User\Application\DTO\InputPeopleDTO;
use Core\User\Application\DTO\InputUserDTO;
use Core\User\Application\UseCase\CheckUserCredentialUseCase;
use Core\User\Application\UseCase\CreateUserUseCase;
use Core\User\Application\UseCase\ListUserUseCase;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request, ListUserUseCase $useCase)
    {
        $response = $useCase->execute(
            inputListUserDTO: new InputListUserDTO(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15),
            )
        );

        return UserListResource::collection($response->items)
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'current_page' => $response->current_page,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from,
                ]
            ]);
    }

    public function login(UserLoginRequest $request, CheckUserCredentialUseCase $useCase)
    {

        $rs = $useCase->execute(
            new InputUserDTO(
                email: $request->email,
                password: $request->password
            )
        );

        if (!$rs) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json(['message' => 'An unexpected error has occurred. Please try again'], 500);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

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
            companyDTO: $inputCompanyDTO
        );

        $httpCode = $response->isSuccess() ? 201 : 400;

        return (new UserResource($response))
            ->response()
            ->setStatusCode($httpCode);
    }
}
