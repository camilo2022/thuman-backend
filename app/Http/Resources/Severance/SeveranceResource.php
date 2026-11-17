<?php

namespace App\Http\Resources\Severance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeveranceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'severance' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'severance_type_id' => $this->severanceTypeId()->value(),
                'reason' => $this->reason()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
