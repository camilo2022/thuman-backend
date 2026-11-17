<?php

namespace App\Http\Resources\DocumentType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'documentTypes' => $this->collection->map(function ($documentType) {
                return [
                    'id' => $documentType->id,
                    'item' => $documentType->item,
                    'person_type' => $documentType->person_type->first(),
                    'name' => $documentType->name,
                    'description' => $documentType->description,
                    'settings' => $documentType->settings,
                    'created_at' => $this->formatDate($documentType->created_at),
                    'updated_at' => $this->formatDate($documentType->updated_at),
                    'deleted_at' => $this->formatDate($documentType->deleted_at)
                ];
            }),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : $date;
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }
}
