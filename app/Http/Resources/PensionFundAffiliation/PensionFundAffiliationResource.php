<?php

namespace App\Http\Resources\PensionFundAffiliation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PensionFundAffiliationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'pensionFundAffiliation' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'country_id' => $this->countryId()->value(),
                'pension_fund_id' => $this->pensionFundId()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
