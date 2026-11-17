<?php

namespace App\Http\Resources\Position\Responsability;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponsabilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'responsability' => [
                'id' => $this->id()->value(),
                'position_id' => $this->positionId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value()
            ],
        ];
    }
}
