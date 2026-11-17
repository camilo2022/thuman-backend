<?php

namespace App\Http\Resources\Relationship;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RelationshipCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'relationships' => $this->collection->map(function ($relationship) {
                return [
                    'id' => $relationship->id,
                    'item' => $relationship->item,
                    'name' => $relationship->name,
                    'description' => $relationship->description,
                    'settings' => $relationship->settings,
                    'created_at' => $this->formatDate($relationship->created_at),
                    'updated_at' => $this->formatDate($relationship->updated_at),
                    'deleted_at' => $this->formatDate($relationship->deleted_at)
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
