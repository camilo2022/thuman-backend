<?php

namespace App\Http\Resources\Vacant\Application\InterviewVideo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewVideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'interviewVideo' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'form_id' => $this->formId()->value(),
                'questions' => $this->questions()->value(),
                'answers' => $this->answers()->value(),
                'reviewer_observation' => $this->reviewerObservation()->value(),
                'reviewer_id' => $this->reviewerId()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
