<?php

namespace App\Http\Resources\SkillType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'skillType' => [
                'id' => $this->id()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => $this->settings()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value()
            ],
        ];
    }
}
