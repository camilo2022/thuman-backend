<?php

namespace App\Http\Resources\LicenseType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LicenseTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'licenseTypes' => $this->collection->map(function ($licenseType) {
                return [
                    'id' => $licenseType->id,
                    'item' => $licenseType->item,
                    'name' => $licenseType->name,
                    'description' => $licenseType->description,
                    'settings' => $licenseType->settings,
                    'created_at' => $this->formatDate($licenseType->created_at),
                    'updated_at' => $this->formatDate($licenseType->updated_at),
                    'deleted_at' => $this->formatDate($licenseType->deleted_at)
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
