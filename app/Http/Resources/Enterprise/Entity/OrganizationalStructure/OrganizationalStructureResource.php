<?php

namespace App\Http\Resources\Enterprise\Entity\OrganizationalStructure;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationalStructureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'organizational_structure' => [
                'id' => $this->id()->value(),
                'entity_id' => $this->entityId()->value(),
                'position_id' => $this->positionId()->value(),
                'parent_position_id' => $this->parentPositionId()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'reason' => $this->reason()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()?->toArray()
            ],
        ];
    }
}
