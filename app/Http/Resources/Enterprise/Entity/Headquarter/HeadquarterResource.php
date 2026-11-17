<?php

namespace App\Http\Resources\Enterprise\Entity\Headquarter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeadquarterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'headquarter' => [
                'id' => $this->id()->value(),
                'entity_id' => $this->entityId()->value(),
                'name' => $this->name()->value(),
                'city_id' => $this->cityId()->value(),
                'address' => $this->address()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
