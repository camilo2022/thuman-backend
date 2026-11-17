<?php

namespace App\Http\Resources\Navigation\Module\Submodule;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubmoduleCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'submodules' => $this->collection->map(function ($submodule) {
                return [
                    'id' => $submodule->id,
                    'model_type' => $submodule->model_type,
                    'model' => $submodule->model,
                    'role' => $submodule->role,
                    'permission' => $submodule->permission,
                    'type' => $submodule->type,
                    'name' => $submodule->name,
                    'route' => $submodule->route,
                    'icon' => $submodule->icon,
                    'submodules' => $submodule->submodules,
                    'created_at' => $this->formatDate($submodule->created_at),
                    'updated_at' => $this->formatDate($submodule->updated_at),
                    'deleted_at' => $this->formatDate($submodule->deleted_at)
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
