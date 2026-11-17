<?php

namespace App\Http\Resources\FileSubtype;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileSubtypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'fileSubtypes' => $this->collection->map(function ($fileSubtype) {
                return [
                    'id' => $fileSubtype->id,
                    'item' => $fileSubtype->item,
                    'name' => $fileSubtype->name,
                    'description' => $fileSubtype->description,
                    'settings' => $fileSubtype->settings,
                    'file_types' => $fileSubtype->file_types,
                    'created_at' => $this->formatDate($fileSubtype->created_at),
                    'updated_at' => $this->formatDate($fileSubtype->updated_at),
                    'deleted_at' => $this->formatDate($fileSubtype->deleted_at)
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
