<?php

namespace App\Http\Resources\ServiceProvider;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceProviderCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'serviceProviders' => $this->collection->map(function ($serviceProvider) {
                return [
                    'id' => $serviceProvider->id,
                    'item' => $serviceProvider->item,
                    'name' => $serviceProvider->name,
                    'description' => $serviceProvider->description,
                    'settings' => $serviceProvider->settings,
                    'service_types' => $serviceProvider->service_types,
                    'countries' => $serviceProvider->countries,
                    'created_at' => $this->formatDate($serviceProvider->created_at),
                    'updated_at' => $this->formatDate($serviceProvider->updated_at),
                    'deleted_at' => $this->formatDate($serviceProvider->deleted_at)
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
