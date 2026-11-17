<?php

namespace App\Http\Resources\ServiceType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'serviceTypes' => $this->collection->map(function ($serviceType) {
                return [
                    'id' => $serviceType->id,
                    'item' => $serviceType->item,
                    'name' => $serviceType->name,
                    'description' => $serviceType->description,
                    'settings' => $serviceType->settings,
                    'created_at' => $this->formatDate($serviceType->created_at),
                    'updated_at' => $this->formatDate($serviceType->updated_at),
                    'deleted_at' => $this->formatDate($serviceType->deleted_at)
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
