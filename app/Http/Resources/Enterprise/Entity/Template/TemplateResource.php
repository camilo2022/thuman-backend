<?php

namespace App\Http\Resources\Enterprise\Entity\Template;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'template' => [
                'id' => $this->id()->value(),
                'model_id' => $this->modelId()->value(),
                'model_type' => $this->modelType()->value(),
                'name' => $this->name()->value(),
                'file_type_id' => $this->fileTypeId()->value(),
                'file_subtype_id' => $this->fileSubtypeId()->value(),
                'path' => $this->path()->value(),
                'mime' => $this->mime()->value(),
                'extension' => $this->extension()->value(),
                'size' => $this->size()->value(),
                'metadata' => $this->metadata()->value(),
                'settings' => $this->settings()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
