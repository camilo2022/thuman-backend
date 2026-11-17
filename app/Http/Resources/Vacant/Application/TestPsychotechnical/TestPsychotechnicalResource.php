<?php

namespace App\Http\Resources\Vacant\Application\TestPsychotechnical;

use App\Models\TestPsychotechnical;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestPsychotechnicalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'testPsychotechnical' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'form_id' => $this->formId()->value(),
                'questions' => $this->questions()->value(),
                'answers' => $this->answers()->value(),
                'psychologist_observation' => $this->psychologistObservation()->value(),
                //'psychologist_score' => $this->psychologistScore()->value(),
                'psychologist_id' => $this->psychologistId()->value(),
                'psychologist_status' => $this->psychologistStatus()->value(),
                'start_at' => $this->startAt()->value(),
                'end_at' => $this->endAt()->value(),
                'indication' => $this->indication()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'statuses' => TestPsychotechnical::statuses()
        ];
    }
}
