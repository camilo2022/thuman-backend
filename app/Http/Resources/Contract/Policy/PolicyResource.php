<?php

namespace App\Http\Resources\Contract\Policy;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'policy' => [
                'id' => $this->id()->value(),
                'uuid' => $this->uuid()->value(),
                'model_id' => $this->modelId()->value(),
                'model_type' => $this->modelType()->value(),
                'risk_manager_id' => $this->riskManagerId()->value(),
                'policy_type_id' => $this->policyTypeId()->value(),
                'code' => $this->code()->value(),
                'value' => $this->value()->value(),
                'insured_value' => $this->insuredValue()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
