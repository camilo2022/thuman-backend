<?php

namespace App\Http\Resources\Enterprise\Entity\Headquarter;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HeadquarterCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'headquarters' => $this->collection->map(function ($headquarter) {
                return [
                    'id' => $headquarter->id,
                    'entity' => $headquarter->entity,
                    'name' => $headquarter->name,
                    'city' => $headquarter->city,
                    'address' => $headquarter->address,
                    'created_at' => $this->formatDate($headquarter->created_at),
                    'updated_at' => $this->formatDate($headquarter->updated_at),
                    'deleted_at' => $this->formatDate($headquarter->deleted_at)
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
