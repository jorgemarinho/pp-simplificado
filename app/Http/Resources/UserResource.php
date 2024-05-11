<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  schema="UserResource",
 *  title="Retorno do Usuário",
 * 	@OA\Property(
 * 		property="success",
 * 		type="boleean"
 * 	),
 * 	@OA\Property(
 * 		property="message",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 	    property="user",
 * 	    type="object"
 * 	),
 *  @OA\Property(
 * 	    property="people",
 * 	    type="object"
 * 	)
 * )
 */
class UserResource extends JsonResource
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
            'user' => $this->getUser()?->toArray(),
            'people' => $this->getPeople()?->toArray(),
        ];
    }
}
