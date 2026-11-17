<?php

namespace App\Http\Resources\Position\RequiredExperience;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequiredExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'requiredExperience' => [
                'id' => $this->id()->value(),
                'position_id' => $this->positionId()->value(),
                'name' => $this->name()->value(),
                'duration_months' => $this->durationMonths()->value(),
                'description' => $this->description()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value()
            ],
        ];
    }
}
