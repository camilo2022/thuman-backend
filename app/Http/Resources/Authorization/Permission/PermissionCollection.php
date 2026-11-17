<?php

namespace App\Http\Resources\Authorization\Permission;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'permissions' => $this->collection->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'title' => $permission->title,
                    'description' => $permission->description,
                    'roles' => $permission->roles,
                    'created_at' => $this->formatDate($permission->created_at),
                    'updated_at' => $this->formatDate($permission->updated_at)
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
