<?php

namespace App\Http\Resources\Disability;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisabilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'disability' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'disability_type_id' => $this->disabilityTypeId()->value(),
                'cie_code_id' => $this->cieCodeId()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'reason' => $this->reason()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
