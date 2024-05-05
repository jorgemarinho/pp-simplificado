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
