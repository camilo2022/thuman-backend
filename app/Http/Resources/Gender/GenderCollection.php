<?php

namespace App\Http\Resources\Gender;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GenderCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'genders' => $this->collection->map(function ($gender) {
                return [
                    'id' => $gender->id,
                    'item' => $gender->item,
                    'name' => $gender->name,
                    'description' => $gender->description,
                    'settings' => $gender->settings,
                    'created_at' => $this->formatDate($gender->created_at),
                    'updated_at' => $this->formatDate($gender->updated_at),
                    'deleted_at' => $this->formatDate($gender->deleted_at)
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
