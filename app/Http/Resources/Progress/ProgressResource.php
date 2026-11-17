<?php

namespace App\Http\Resources\Progress;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'progress' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'step_id' => $this->stepId()->value(),
                'questions' => $this->questions()->value(),
                'answers' => $this->answers()->value(),
                'start_at' => $this->startAt()->value(),
                'end_at' => $this->endAt()->value(),
                'status' => $this->status()->value(),
                'result' => $this->result()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
