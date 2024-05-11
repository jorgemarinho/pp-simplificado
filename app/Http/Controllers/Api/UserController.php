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

    /**
     * User login.
     *
     * @param UserLoginRequest $request the request object.
     * @param CheckUserCredentialUseCase $useCase the use case for checking user credentials.
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/login",
     *     summary="Ação para realizar o login do usuário, para usar o token deve adicionar 'Bearer seuToken' no Authorize",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="token",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="email",
     *                  description="O campo email deve ser um e-mail válido",
     *                  type="array",
     *                  @OA\Items(type="string")
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  description="O campo password deve ter no mínimo 8 caracteres",
     *                  type="array",
     *                  @OA\Items(type="string")
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error has occurred",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
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

        if (!$user) {
            return response()->json(['message' => 'An unexpected error has occurred. Please try again'], 500);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    /**
     * A Requisição devem possuir no header 
     * Authorization: Bearer <ACCESS_TOKEN>
     *
     * @param Request $request The HTTP request object.
     * @param ListUserUseCase $useCase The use case for listing users.
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/users",
     *     summary="Lista todos os usuários cadastrado",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter the users by a specific value",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Sort the users in ascending or descending order",
     *         required=false,
     *         @OA\Schema(type="string", enum={"ASC", "DESC"})
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The current page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="total_page",
     *         in="query",
     *         description="The total number of pages",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/UserListResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */
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

    /**
     * Gravação de um novo usuário.
     *
     * @param UserRequest $request o objeto de request.
     * @param CreateUserUseCase $createUserUseCase o caso de uso de criação de usuário.
     * @return \Illuminate\Http\JsonResponse
     *
     * 
     *
     * @OA\Post(
     *     path="/users",
     *     summary="Criação de um novo usuário",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="full_name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="cpf",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="phone",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="cnpj",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/UserResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error has occurred",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     * 
     * */
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
