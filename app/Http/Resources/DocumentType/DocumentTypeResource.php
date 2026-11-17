<?php

namespace App\Http\Resources\DocumentType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'documentType' => [
                'id' => $this->id()->value(),
                'item_id' => $this->itemId()->value(),
                'person_type_id' => $this->personTypeId()->value(),
                'name' => $this->name()->value(),
                'description' => $this->description()->value(),
                'settings' => [
                    'abreviature' => $this->settings()->abreviature(),
                    'validations' => $this->settings()->validations()
                ],
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
