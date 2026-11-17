<?php

namespace App\Http\Resources\CertificateType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CertificateTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'certificateTypes' => $this->collection->map(function ($certificateType) {
                return [
                    'id' => $certificateType->id,
                    'item' => $certificateType->item,
                    'name' => $certificateType->name,
                    'description' => $certificateType->description,
                    'settings' => $certificateType->settings,
                    'created_at' => $this->formatDate($certificateType->created_at),
                    'updated_at' => $this->formatDate($certificateType->updated_at),
                    'deleted_at' => $this->formatDate($certificateType->deleted_at)
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
