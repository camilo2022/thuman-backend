<?php

namespace App\Http\Resources\Navigation\Module\Submodule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmoduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'submodule' => [
                'id' => $this->id()->value(),
                'model_id' => $this->modelId()->value(),
                'model_type' => $this->modelType()->value(),
                'role_id' => $this->roleId()->value(),
                'permission_id' => $this->permissionId()->value(),
                'name' => $this->name()->value(),
                'type' => $this->type()->value(),
                'route' => $this->route()->value(),
                'icon' => $this->icon()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
