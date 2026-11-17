<?php

namespace App\Http\Resources\Vacant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'vacant' => [
                'id' => $this->id()->value(),
                'uuid' => $this->uuid()->value(),
                'hiring_reason_id' => $this->hiringReasonId()->value(),
                'position_id' => $this->positionId()->value(),
                'person_id' => $this->personId()->value(),
                'headquarter_id' => $this->headquarterId()->value(),
                'contract_type_id' => $this->contractTypeId()->value(),
                'work_arrangement_id' => $this->workArrangementId()->value(),
                'work_day_id' => $this->workDayId()->value(),
                'salary_min' => $this->salaryMin()->value(),
                'salary_max' => $this->salaryMax()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'description' => $this->description()->value(),
                'quantity' => $this->quantity()->value(),
                'status' => $this->status()->value(),
                'settings' => $this->settings()->value(),
                'benefits_id' => $this->benefitsId()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
