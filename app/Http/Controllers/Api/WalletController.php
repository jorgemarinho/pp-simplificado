<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCreditWalletRequest;
use App\Http\Requests\TransferWalletRequest;
use App\Http\Resources\WalletResource;
use Core\Wallet\Application\DTO\InputAddCreditWalletDTO;
use Core\Wallet\Application\DTO\InputTransferWalletDTO;
use Core\Wallet\Application\UseCase\AddCreditUseCase;
use Core\Wallet\Application\UseCase\TransferUseCase;
use Core\SeedWork\Domain\ValueObjects\Uuid as ValueObjectUuid;

class WalletController extends Controller
{
    /**
     * @OA\Post(
     *     path="/wallets/add-credit",
     *     summary="Adicionar credito para carteira",
     *     tags={"Wallet"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user_id",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="amount",
     *                 type="float"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Credit added successfully",
     *         @OA\JsonContent(ref="#/components/schemas/WalletResource")
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
     *     )
     * )
     */
    public function addCredit(AddCreditWalletRequest $request, AddCreditUseCase $addCreditUseCase)
    {
        $inputAddCreditWalletDTO = new InputAddCreditWalletDTO(
            userId:  new ValueObjectUuid($request->user_id),
            amount: $request->amount
        );

        $response = $addCreditUseCase->execute($inputAddCreditWalletDTO);

        $httpCode = $response->isSuccess() ? 201 : 400;

        return (new WalletResource($response))
            ->response()
            ->setStatusCode($httpCode);
    }

    /**
     * @OA\Post(
     *     path="/wallets/transfer",
     *     summary="Realiza transferência entre carteiras",
     *     tags={"Wallet"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="payer_user_id",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="payee_user_id",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="value",
     *                 type="float"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transfer successful",
     *         @OA\JsonContent(ref="#/components/schemas/WalletResource")
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
     *     )
     * )
     */
    public function transfer(TransferWalletRequest $request, TransferUseCase $transferUseCase)
    {
        $inputTransferWalletDTO = new InputTransferWalletDTO(
            payerUserId: new ValueObjectUuid($request->payer_user_id),
            payeeUserId: new ValueObjectUuid($request->payee_user_id),
            value: $request->value,
        );

        $response = $transferUseCase->execute($inputTransferWalletDTO);

        $httpCode = $response->isSuccess() ? 200 : 400;

        return (new WalletResource($response))
            ->response()
            ->setStatusCode($httpCode);
    }
}
