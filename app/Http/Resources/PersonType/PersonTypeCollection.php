<?php

namespace App\Http\Resources\PersonType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'personTypes' => $this->collection->map(function ($personType) {
                return [
                    'id' => $personType->id,
                    'item' => $personType->item,
                    'name' => $personType->name,
                    'description' => $personType->description,
                    'settings' => $personType->settings,
                    'document_types' => $personType->document_types,
                    'created_at' => $this->formatDate($personType->created_at),
                    'updated_at' => $this->formatDate($personType->updated_at),
                    'deleted_at' => $this->formatDate($personType->deleted_at)
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
