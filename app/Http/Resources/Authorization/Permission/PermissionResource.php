<?php

namespace App\Http\Resources\Authorization\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'permission' => [
                'id' => $this->id()->value(),
                'name' => $this->name()->value(),
                'title' => $this->title()->value(),
                'description' => $this->description()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
