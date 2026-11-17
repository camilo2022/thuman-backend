<?php

namespace App\Http\Resources\Sex;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SexCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'sexes' => $this->collection->map(function ($sex) {
                return [
                    'id' => $sex->id,
                    'item' => $sex->item,
                    'name' => $sex->name,
                    'description' => $sex->description,
                    'settings' => $sex->settings,
                    'created_at' => $this->formatDate($sex->created_at),
                    'updated_at' => $this->formatDate($sex->updated_at),
                    'deleted_at' => $this->formatDate($sex->deleted_at)
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
