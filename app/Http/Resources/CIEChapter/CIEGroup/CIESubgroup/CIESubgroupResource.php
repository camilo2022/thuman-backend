<?php

namespace App\Http\Resources\CIEChapter\CIEGroup\CIESubgroup;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CIESubgroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'cieSubgroup' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'cie_group_id' => $this->cieGroupId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => $this->settings()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
