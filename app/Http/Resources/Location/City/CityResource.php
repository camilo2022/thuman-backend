<?php

namespace App\Http\Resources\Location\City;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'city' => [
                'id' => $this->id()->value(),
                'dapartament_id' => $this->countryId()->value(),
                'name' => $this->name()->value(),
                'latitude' => $this->latitude()->value(),
                'longitude' => $this->longitude()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
            ],
        ];
    }
}
