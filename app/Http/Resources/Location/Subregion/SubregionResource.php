<?php

namespace App\Http\Resources\Location\Subregion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubregionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'subregion' => [
                'id' => $this->id()->value(),
                'region_id' => $this->regionId()->value(),
                'name' => $this->name()->value(),
                'translations' => $this->translations()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
            ],
        ];
    }
}
