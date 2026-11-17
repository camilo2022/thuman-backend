<?php

namespace App\Http\Resources\DisabilityType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DisabilityTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'disabilityTypes' => $this->collection->map(function ($disabilityType) {
                return [
                    'id' => $disabilityType->id,
                    'item' => $disabilityType->item,
                    'name' => $disabilityType->name,
                    'description' => $disabilityType->description,
                    'settings' => $disabilityType->settings,
                    'created_at' => $this->formatDate($disabilityType->created_at),
                    'updated_at' => $this->formatDate($disabilityType->updated_at),
                    'deleted_at' => $this->formatDate($disabilityType->deleted_at)
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
