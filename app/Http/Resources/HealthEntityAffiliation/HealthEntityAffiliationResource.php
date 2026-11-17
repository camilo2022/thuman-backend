<?php

namespace App\Http\Resources\HealthEntityAffiliation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthEntityAffiliationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'healthEntityAffiliation' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'country_id' => $this->countryId()->value(),
                'health_entity_id' => $this->healthEntityId()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
