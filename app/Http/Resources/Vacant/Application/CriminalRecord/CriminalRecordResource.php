<?php

namespace App\Http\Resources\Vacant\Application\CriminalRecord;

use App\Models\CriminalRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CriminalRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'criminalRecord' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'statuses' => CriminalRecord::statuses()
        ];
    }
}
