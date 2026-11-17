<?php

namespace App\Http\Resources\FileSubtype;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileSubtypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'fileSubtype' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
