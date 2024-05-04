<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
 
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
            'user' => $this->getUser()->toArray(),
            'people' => $this->getPeople()->toArray(),
        ];
    }
}