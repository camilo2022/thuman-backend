<?php

namespace App\Http\Resources\TerminationType\Reason;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReasonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reason' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'termination_type_id' => $this->terminationTypeId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => $this->settings()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
