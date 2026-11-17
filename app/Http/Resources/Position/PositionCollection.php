<?php

namespace App\Http\Resources\Position;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PositionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'positions' => $this->collection->map(function ($position) {
                return [
                    'id' => $position->id,
                    'area' => $position->area,
                    'name' => $position->name,
                    'profile_category' => $position->profile_category,
                    'description' => $position->description,
                    'settings' => $position->settings,
                    'created_at' => $this->formatDate($position->created_at),
                    'updated_at' => $this->formatDate($position->updated_at),
                    'deleted_at' => $this->formatDate($position->deleted_at)
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
