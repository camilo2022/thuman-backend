<?php

namespace App\Http\Resources\Termination;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TerminationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'termination' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'model_id' => $this->modelId()->value(),
                'model_type' => $this->modelType()->value(),
                'reason' => $this->reason()->value(),
                'decision' => $this->decision()->value(),
                'date' => $this->date()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
