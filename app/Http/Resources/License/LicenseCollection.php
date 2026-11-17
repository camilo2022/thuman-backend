<?php

namespace App\Http\Resources\License;

use App\Models\License;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LicenseCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'licenses' => $this->collection->map(function ($license) {
                return [
                    'id' => $license->id,
                    'person' => $license->person,
                    'license_type' => $license->license_type,
                    'start_date' => $this->formatDate($license->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($license->end_date, 'Y-m-d'),
                    'reason' => $license->reason,
                    'status' => $license->status,
                    'created_at' => $this->formatDate($license->created_at),
                    'updated_at' => $this->formatDate($license->updated_at)
                ];
            }),
            'statuses' => License::statuses(),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return $date ? Carbon::parse($date)->format($format) : $date;
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
