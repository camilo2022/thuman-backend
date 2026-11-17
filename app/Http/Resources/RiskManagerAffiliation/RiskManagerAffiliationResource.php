<?php

namespace App\Http\Resources\RiskManagerAffiliation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiskManagerAffiliationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'riskManagerAffiliation' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'country_id' => $this->countryId()->value(),
                'risk_manager_id' => $this->riskManagerId()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
