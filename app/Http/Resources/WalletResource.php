<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  schema="WalletResource",
 *  title="Wallet Resource",
 * 	@OA\Property(
 * 		property="success",
 * 		type="boleean"
 * 	),
 * 	@OA\Property(
 * 		property="message",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 	    property="balance",
 * 	    type="float"
 * 	)
 * )
 */
class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->isSuccess(),
            'message' => $this->getMessage(),
            'balance' => $this->getBalance(),
        ];
    }
}