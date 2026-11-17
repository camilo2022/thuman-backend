<?php

namespace App\Http\Resources\Benefit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BenefitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'benefit' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => $this->settings()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'icon' => $this->icon() ? [
                    'id' => $this->icon()->id()?->value(),
                    'model_id' => $this->icon()->modelId()->value(),
                    'model_type' => $this->icon()->modelType()->value(),
                    'file_type_id' => $this->icon()->fileTypeId()->value(),
                    'file_subtype_id' => $this->icon()->fileSubtypeId()->value(),
                    'name' => $this->icon()->name()->value(),
                    'path' => $this->icon()->path()->value(),
                    'mime' => $this->icon()->mime()->value(),
                    'extension' => $this->icon()->extension()->value(),
                    'size' => $this->icon()->size()->value(),
                    'metadata' => $this->icon()->metadata()->value(),
                    'created_at' => $this->icon()->createdAt()?->value(),
                    'updated_at' => $this->icon()->updatedAt()?->value(),
                    'deleted_at' => $this->icon()->deletedAt()?->value(),
                ] : null,
            ],
        ];
    }
}
