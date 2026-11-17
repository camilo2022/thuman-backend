<?php

namespace App\Http\Resources\Location\Departament;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartamentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'departament' => [
                'id' => $this->id()->value(),
                'country_id' => $this->countryId()->value(),
                'name' => $this->name()->value(),
                'state_code' => $this->stateCode()->value(),
                'type' => $this->type()->value(),
                'latitude' => $this->latitude()->value(),
                'longitude' => $this->longitude()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
            ],
        ];
    }
}
