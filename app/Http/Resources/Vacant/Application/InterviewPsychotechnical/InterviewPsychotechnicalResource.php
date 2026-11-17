<?php

namespace App\Http\Resources\Vacant\Application\InterviewPsychotechnical;

use App\Models\InterviewPsychotechnical;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewPsychotechnicalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'interviewPsychotechnical' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'date_time' => $this->dateTime()->value(),
                'questions' => $this->questions()->value(),
                'answers' => $this->answers()->value(),
                'psychologist_observation' => $this->psychologistObservation()->value(),
                'psychologist_score' => $this->psychologistScore()->value(),
                'psychologist_id' => $this->psychologistId()->value(),
                'psychologist_status' => $this->psychologistStatus()->value(),
                'indication' => $this->indication()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'statuses' => InterviewPsychotechnical::statuses()
        ];
    }
}
