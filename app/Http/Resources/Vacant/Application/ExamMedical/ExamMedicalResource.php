<?php

namespace App\Http\Resources\Vacant\Application\ExamMedical;

use App\Models\ExamMedical;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamMedicalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'examMedical' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'date_time' => $this->dateTime()->value(),
                'city_id' => $this->cityId()->value(),
                'health_entity_id' => $this->healthEntityId()->value(),
                'address' => $this->address()->value(),
                'neighborhood' => $this->neighborhood()->value(),
                'result' => $this->result()->value(),
                'epidemiological_program_id' => $this->epidemiologicalProgramId()->value(),
                'description' => $this->description()->value(),
                'indication' => $this->indication()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'statuses' => ExamMedical::statuses()
        ];
    }
}
