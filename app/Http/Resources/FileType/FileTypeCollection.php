<?php

namespace App\Http\Resources\FileType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'fileTypes' => $this->collection->map(function ($fileType) {
                return [
                    'id' => $fileType->id,
                    'item' => $fileType->item,
                    'name' => $fileType->name,
                    'description' => $fileType->description,
                    'settings' => $fileType->settings,
                    'file_subtypes' => $fileType->file_subtypes,
                    'created_at' => $this->formatDate($fileType->created_at),
                    'updated_at' => $this->formatDate($fileType->updated_at),
                    'deleted_at' => $this->formatDate($fileType->deleted_at)
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
