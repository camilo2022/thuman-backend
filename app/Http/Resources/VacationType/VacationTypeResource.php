<?php

namespace App\Http\Resources\VacationType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacationTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'vacationType' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => [
                    'field_type' => $this->settings()->fieldType(),
                    'message' => $this->settings()->message(),
                    'validations' => $this->settings()->validations()
                ],
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
