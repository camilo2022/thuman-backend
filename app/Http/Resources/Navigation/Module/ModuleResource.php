<?php

namespace App\Http\Resources\Navigation\Module;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'module' => [
                'id' => $this->id()->value(),
                'name' => $this->name()->value(),
                'type' => $this->type()->value(),
                'route' => $this->route()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value()
            ],
        ];
    }
}
