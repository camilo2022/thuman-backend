<?php

namespace App\Http\Resources\Location\Departament;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartamentCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'departaments' => $this->collection->map(function ($departament) {
                return [
                    'id' => $departament->id,
                    'country' => $departament->country,
                    'name' => $departament->name,
                    'state_code' => $departament->state_code,
                    'type' => $departament->type,
                    'latitude' => $departament->latitude,
                    'longitude' => $departament->longitude,
                    'cities' => $departament->cities,
                    'created_at' => $this->formatDate($departament->created_at),
                    'updated_at' => $this->formatDate($departament->updated_at)
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
