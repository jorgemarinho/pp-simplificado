<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserListResource",
 *     title="Listagem do usuario",
 *     description="Dados do usuario",
 * 	@OA\Property(
 * 		property="id",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="email",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="cpf",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="created_at",
 * 		type="string"
 * 	),
 *   @OA\Property(
 * 		property="updated_at",
 * 		type="string"
 * 	),
 *   
 * )
 *
 */
class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user_id,
            'email' => $this->email,
            'name' => $this->full_name,
            'cpf' => $this->cpf,
            'user_type' => $this->user_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
