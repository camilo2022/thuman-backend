<?php

namespace App\Http\Resources\Profile\ProfessionalExperience;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'professionalExperience' => [
                'id' => $this->id()->value(),
                'profile_id' => $this->profileId()->value(),
                'company_name' => $this->companyName()->value(),
                'supervisor_name' => $this->supervisorName()->value(),
                'supervisor_dialing_country_id' => $this->supervisorDialingCountryId()->value(),
                'supervisor_phone_number' => $this->supervisorPhoneNumber()->value(),
                'city_id' => $this->cityId()->value(),
                'company_address' => $this->companyAddress()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'position' => $this->position()->value(),
                'work_arrangement_id' => $this->workArrangementId()->value(),
                'position_description' => $this->positionDescription()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value()
            ],
        ];
    }
}
