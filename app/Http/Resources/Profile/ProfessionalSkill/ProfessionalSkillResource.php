<?php

namespace App\Http\Resources\Profile\ProfessionalSkill;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalSkillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'professionalSkill' =>  [
                'id' => $this->id()->value(),
                'profile_id' => $this->profileId()->value(),
                'skill_id' => $this->skillId()->value(),
                'percentage' => $this->percentage()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
            ],
        ];
    }
}
