<?php

namespace App\Http\Resources\TerminationType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TerminationTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'terminationType' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => [
                    'reason_required' => $this->settings()->reasonRequired(),
                    'file_required' => $this->settings()->fileRequired(),
                    'massive_allowed' => $this->settings()->massiveAllowed(),
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
