<?php

namespace App\Http\Resources\EntityType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EntityTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'entityTypes' => $this->collection->map(function ($entityType) {
                return [
                    'id' => $entityType->id,
                    'item' => $entityType->item,
                    'name' => $entityType->name,
                    'description' => $entityType->description,
                    'settings' => $entityType->settings,
                    'created_at' => $this->formatDate($entityType->created_at),
                    'updated_at' => $this->formatDate($entityType->updated_at),
                    'deleted_at' => $this->formatDate($entityType->deleted_at)
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
