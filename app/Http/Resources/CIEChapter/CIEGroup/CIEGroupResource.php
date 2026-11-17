<?php

namespace App\Http\Resources\CIEChapter\CIEGroup;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CIEGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'cieGroup' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'cie_chapter_id' => $this->cieChapterId()->value(),
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
