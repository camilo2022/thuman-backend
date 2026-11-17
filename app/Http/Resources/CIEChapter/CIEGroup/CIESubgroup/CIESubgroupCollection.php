<?php

namespace App\Http\Resources\CIEChapter\CIEGroup\CIESubgroup;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CIESubgroupCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'cieSubgroups' => $this->collection->map(function ($cieSubgroup) {
                return [
                    'id' => $cieSubgroup->id,
                    'item' => $cieSubgroup->item,
                    'cie_group' => $cieSubgroup->cie_group->first(),
                    'name' => $cieSubgroup->name,
                    'description' => $cieSubgroup->description,
                    'settings' => $cieSubgroup->settings,
                    'created_at' => $this->formatDate($cieSubgroup->created_at),
                    'updated_at' => $this->formatDate($cieSubgroup->updated_at),
                    'deleted_at' => $this->formatDate($cieSubgroup->deleted_at)
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
