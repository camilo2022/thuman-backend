<?php

namespace App\Http\Resources\Induction\Step;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'step' => [
                'id' => $this->id()->value(),
                'induction_id' => $this->inductionId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'form_id' => $this->formId()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
