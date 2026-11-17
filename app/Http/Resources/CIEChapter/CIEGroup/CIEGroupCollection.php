<?php

namespace App\Http\Resources\CIEChapter\CIEGroup;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CIEGroupCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'cieGroups' => $this->collection->map(function ($cieGroup) {
                return [
                    'id' => $cieGroup->id,
                    'item' => $cieGroup->item,
                    'cie_chapter' => $cieGroup->cie_chapter->first(),
                    'name' => $cieGroup->name,
                    'description' => $cieGroup->description,
                    'settings' => $cieGroup->settings,
                    'created_at' => $this->formatDate($cieGroup->created_at),
                    'updated_at' => $this->formatDate($cieGroup->updated_at),
                    'deleted_at' => $this->formatDate($cieGroup->deleted_at)
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
