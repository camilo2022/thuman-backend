<?php

namespace App\Http\Resources\AddendumType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddendumTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'addendumTypes' => $this->collection->map(function ($addendumType) {
                return [
                    'id' => $addendumType->id,
                    'item' => $addendumType->item,
                    'name' => $addendumType->name,
                    'description' => $addendumType->description,
                    'settings' => $addendumType->settings,
                    'created_at' => $this->formatDate($addendumType->created_at),
                    'updated_at' => $this->formatDate($addendumType->updated_at),
                    'deleted_at' => $this->formatDate($addendumType->deleted_at)
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
