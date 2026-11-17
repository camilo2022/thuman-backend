<?php

namespace App\Http\Resources\Position\AcademicRequirement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicRequirementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'academicRequirement' => [
                'id' => $this->id()->value(),
                'position_id' => $this->positionId()->value(),
                'academic_level_id' => $this->academicLevelId()->value(),
                'academic_title' => $this->academicTitle()->value(),
                'formation_status_id' => $this->formationStatusId()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value()
            ],
        ];
    }
}
