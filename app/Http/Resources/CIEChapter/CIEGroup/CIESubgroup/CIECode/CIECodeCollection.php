<?php

namespace App\Http\Resources\CIEChapter\CIEGroup\CIESubgroup\CIECode;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CIECodeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'cieCodes' => $this->collection->map(function ($cieCode) {
                return [
                    'id' => $cieCode->id,
                    'item' => $cieCode->item,
                    'cie_subgroup' => $cieCode->cie_subgroup->first(),
                    'name' => $cieCode->name,
                    'description' => $cieCode->description,
                    'settings' => $cieCode->settings,
                    'created_at' => $this->formatDate($cieCode->created_at),
                    'updated_at' => $this->formatDate($cieCode->updated_at),
                    'deleted_at' => $this->formatDate($cieCode->deleted_at)
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
