<?php

namespace App\Http\Resources\Vacant\Application\TestTechnical;

use App\Models\TestTechnical;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestTechnicalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'testTechnical' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'start_date' => $this->startDate()->value(),
                'end_date' => $this->endDate()->value(),
                'form_id' => $this->formId()->value(),
                'questions' => $this->questions()->value(),
                'answers' => $this->answers()->value(),
                'leader_observation' => $this->leaderObservation()->value(),
                //'leader_score' => $this->leaderScore()->value(),
                'leader_id' => $this->leaderId()->value(),
                'leader_status' => $this->leaderStatus()->value(),
                'start_at' => $this->startAt()->value(),
                'end_at' => $this->endAt()->value(),
                'indication' => $this->indication()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'statuses' => TestTechnical::statuses()
        ];
    }
}
