<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'password' => Str::mask($this->password()->value(), '*', 5),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
