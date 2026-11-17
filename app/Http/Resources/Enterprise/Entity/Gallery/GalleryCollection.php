<?php

namespace App\Http\Resources\Enterprise\Entity\Gallery;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GalleryCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'galleries' => $this->collection->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'model' => $gallery->model,
                    'name' => $gallery->name,
                    'file_type' => $gallery->file_type,
                    'file_subtype' => $gallery->file_subtype,
                    'path' => $gallery->path,
                    'mime' => $gallery->mime,
                    'extension' => $gallery->extension,
                    'size' => $gallery->size,
                    'metadata' => $gallery->metadata,
                    'created_at' => $this->formatDate($gallery->created_at),
                    'updated_at' => $this->formatDate($gallery->updated_at),
                    'deleted_at' => $this->formatDate($gallery->deleted_at)
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
