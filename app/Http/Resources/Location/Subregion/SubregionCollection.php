<?php

namespace App\Http\Resources\Location\Subregion;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubregionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'regions' => $this->collection->map(function ($subregion) {
                return [
                    'id' => $subregion->id,
                    'region' => $subregion->region,
                    'name' => $subregion->name,
                    'translations' => $subregion->translations,
                    'countries' => $subregion->countries,
                    'created_at' => $this->formatDate($subregion->created_at),
                    'updated_at' => $this->formatDate($subregion->updated_at)
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
