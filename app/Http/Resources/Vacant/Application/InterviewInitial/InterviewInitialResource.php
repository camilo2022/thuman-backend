<?php

namespace App\Http\Resources\Vacant\Application\InterviewInitial;

use App\Models\InterviewInitial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewInitialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'interviewInitial' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'date_time' => $this->dateTime()->value(),
                'form_id' => $this->formId()->value(),
                'questions' => $this->questions()->value(),
                'answers' => $this->answers()->value(),
                'salary_min' => $this->salaryMin()->value(),
                'salary_max' => $this->salaryMax()->value(),
                'reviewer_observation' => $this->reviewerObservation()->value(),
                'reviewer_score' => $this->reviewerScore()->value(),
                'reviewer_id' => $this->reviewerId()->value(),
                'reviewer_status' => $this->reviewerStatus()->value(),
                'leader_observation' => $this->leaderObservation()->value(),
                'leader_score' => $this->leaderScore()->value(),
                'leader_id' => $this->leaderId()->value(),
                'leader_status' => $this->leaderStatus()->value(),
                'indication' => $this->indication()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'statuses' => InterviewInitial::statuses()
        ];
    }
}
