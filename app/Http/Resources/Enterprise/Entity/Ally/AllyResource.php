<?php

namespace App\Http\Resources\Enterprise\Entity\Ally;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ally' => [
                'id' => $this->id()->value(),
                'entity_id' => $this->entityId()->value(),
                'name' => $this->name()->value(),
                'country_id' => $this->countryId()->value(),
                'description' => $this->description()->value(),
                'settings' => $this->settings()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
