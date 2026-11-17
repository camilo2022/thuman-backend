<?php

namespace App\Http\Resources\Navigation\Module;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'modules' => $this->collection->map(function ($module) {
                return [
                    'id' => $module->id,
                    'name' => $module->name,
                    'type' => $module->type,
                    'route' => $module->route,
                    'submodules' => $module->submodules,
                    'created_at' => $this->formatDate($module->created_at),
                    'updated_at' => $this->formatDate($module->updated_at),
                    'deleted_at' => $this->formatDate($module->deleted_at)
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
