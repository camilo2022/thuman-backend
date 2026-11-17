<?php

namespace App\Http\Resources\Location\Region;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'region' => [
                'id' => $this->id()->value(),
                'name' => $this->name()->value(),
                'translations' => $this->translations()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
            ],
        ];
    }
}
