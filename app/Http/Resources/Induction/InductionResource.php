<?php

namespace App\Http\Resources\Induction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InductionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'induction' => [
                'id' => $this->id()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'assignable_id' => $this->assignableId()->value(),
                'assignable_type' => $this->assignableType()->value(),
                'model_id' => $this->modelId()->value(),
                'model_type' => $this->modelType()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
