<?php

namespace App\Http\Resources\Profile\AcademicQualification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicQualificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'academicQualification' => [
                'id' => $this->id()->value(),
                'profile_id' => $this->profileId()->value(),
                'city_id' => $this->cityId()->value(),
                'academic_level_id' => $this->academicLevelId()->value(),
                'academic_institution' => $this->academicInstitution()->value(),
                'academic_title' => $this->academicTitle()->value(),
                'formation_status_id' => $this->formationStatusId()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
