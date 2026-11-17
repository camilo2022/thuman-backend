<?php

namespace App\Http\Resources\CompensationFundAffiliation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompensationFundAffiliationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'compensationFundAffiliation' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'country_id' => $this->countryId()->value(),
                'compensation_fund_id' => $this->compensationFundId()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
