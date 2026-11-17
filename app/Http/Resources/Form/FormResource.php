<?php

namespace App\Http\Resources\Form;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'form' => [
                'id' => $this->id()->value(),
                'form_type_id' => $this->formTypeId()->value(),
                'assignable_id' => $this->assignableId()->value(),
                'assignable_type' => $this->assignableType()->value(),
                'model_id' => $this->modelId()->value(),
                'model_type' => $this->modelType()->value(),
                'questions' => $this->questions()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
