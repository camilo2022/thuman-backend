<?php

namespace App\Http\Resources\Resignation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResignationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'resignation' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'resignation_type_id' => $this->resignationTypeId()->value(),
                'date' => $this->date()->value(),
                'reason' => $this->reason()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
