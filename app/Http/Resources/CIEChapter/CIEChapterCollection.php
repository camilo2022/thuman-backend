<?php

namespace App\Http\Resources\CIEChapter;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CIEChapterCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'cieChapters' => $this->collection->map(function ($cieChapter) {
                return [
                    'id' => $cieChapter->id,
                    'item' => $cieChapter->item,
                    'name' => $cieChapter->name,
                    'description' => $cieChapter->description,
                    'settings' => $cieChapter->settings,
                    'created_at' => $this->formatDate($cieChapter->created_at),
                    'updated_at' => $this->formatDate($cieChapter->updated_at),
                    'deleted_at' => $this->formatDate($cieChapter->deleted_at)
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
