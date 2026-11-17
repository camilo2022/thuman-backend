<?php

namespace App\Http\Resources\Vacant\Application\ExamPolygraph;

use App\Models\ExamPolygraph;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamPolygraphResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'examPolygraph' => [
                'id' => $this->id()->value(),
                'application_id' => $this->applicationId()->value(),
                'date_time' => $this->dateTime()->value(),
                'city_id' => $this->cityId()->value(),
                'service_provider_id' => $this->serviceProviderId()->value(),
                'address' => $this->address()->value(),
                'neighborhood' => $this->neighborhood()->value(),
                'result' => $this->result()->value(),
                'description' => $this->description()->value(),
                'indication' => $this->indication()->value(),
                'status' => $this->status()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'statuses' => ExamPolygraph::statuses()
        ];
    }
}
