<?php

namespace App\Http\Resources\Location\City;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'cities' => $this->collection->map(function ($city) {
                return [
                    'id' => $city->id,
                    'departament' => $city->departament,
                    'name' => $city->name,
                    'latitude' => $city->latitude,
                    'longitude' => $city->longitude,
                    'created_at' => $this->formatDate($city->created_at),
                    'updated_at' => $this->formatDate($city->updated_at)
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
