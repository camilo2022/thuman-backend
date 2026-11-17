<?php

namespace App\Http\Resources\Enterprise\Entity\Ally;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllyCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'allies' => $this->collection->map(function ($ally) {
                return [
                    'id' => $ally->id,
                    'entity' => $ally->entity,
                    'name' => $ally->name,
                    'country' => $ally->country,
                    'description' => $ally->description,
                    'settings' => $ally->settings,
                    'created_at' => $this->formatDate($ally->created_at),
                    'updated_at' => $this->formatDate($ally->updated_at),
                    'deleted_at' => $this->formatDate($ally->deleted_at)
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
