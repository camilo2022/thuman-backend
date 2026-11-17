<?php

namespace App\Http\Resources\HealthEntity;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HealthEntityCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'healthEntities' => $this->collection->map(function ($healthEntity) {
                return [
                    'id' => $healthEntity->id,
                    'item' => $healthEntity->item,
                    'name' => $healthEntity->name,
                    'description' => $healthEntity->description,
                    'settings' => $healthEntity->settings,
                    'health_entity_type' => $healthEntity->health_entity_type->first(),
                    'created_at' => $this->formatDate($healthEntity->created_at),
                    'updated_at' => $this->formatDate($healthEntity->updated_at),
                    'deleted_at' => $this->formatDate($healthEntity->deleted_at)
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
