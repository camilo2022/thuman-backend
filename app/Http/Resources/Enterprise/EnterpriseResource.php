<?php

namespace App\Http\Resources\Enterprise;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnterpriseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        
        $logo = $this->logo();

        return [
            'enterprise' => [
                'id' => $this->id()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'logo' => $logo ? [
                    'id' => $logo->id()?->value(),
                    'model_id' => $logo->modelId()->value(),
                    'model_type' => $logo->modelType()->value(),
                    'file_type_id' => $logo->fileTypeId()->value(),
                    'file_subtype_id' => $logo->fileSubtypeId()->value(),
                    'name' => $logo->name()->value(),
                    'path' => $logo->path()->value(),
                    'mime' => $logo->mime()->value(),
                    'extension' => $logo->extension()->value(),
                    'size' => $logo->size()->value(),
                    'metadata' => $logo->metadata()->value(),
                    'created_at' => $logo->createdAt()?->value(),
                    'updated_at' => $logo->updatedAt()?->value(),
                    'deleted_at' => $logo->deletedAt()?->value(),
                ] : null,
            ],
        ];
    }
}
