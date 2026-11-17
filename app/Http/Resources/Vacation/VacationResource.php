<?php

namespace App\Http\Resources\Vacation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'vacation' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'vacation_type_id' => $this->vacationTypeId()->value(),
                'period' => $this->period()->value(),
                'days' => $this->days()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
