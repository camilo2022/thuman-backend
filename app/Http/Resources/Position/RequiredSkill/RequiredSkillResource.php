<?php

namespace App\Http\Resources\Position\RequiredSkill;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequiredSkillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'requiredSkill' => [
                'id' => $this->id()->value(),
                'position_id' => $this->positionId()->value(),
                'skill_id' => $this->skillId()->value(),
                'percentage' => $this->percentage()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value()
            ],
        ];
    }
}
