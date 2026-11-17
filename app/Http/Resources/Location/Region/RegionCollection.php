<?php

namespace App\Http\Resources\Location\Region;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RegionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'regions' => $this->collection->map(function ($region) {
                return [
                    'id' => $region->id,
                    'name' => $region->name,
                    'translations' => $region->translations,
                    'subregions' => $region->subregions,
                    'created_at' => $this->formatDate($region->created_at),
                    'updated_at' => $this->formatDate($region->updated_at)
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
