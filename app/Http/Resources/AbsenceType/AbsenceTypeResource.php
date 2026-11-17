<?php

namespace App\Http\Resources\AbsenceType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsenceTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'absenceType' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => [
                    'compensation_enabled' => $this->settings()->compensationEnabled(),
                    'file_required' => $this->settings()->fileRequired(),
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
