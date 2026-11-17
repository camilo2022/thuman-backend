<?php

namespace App\Http\Resources\Severance;

use App\Models\Severance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeveranceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'severances' => $this->collection->map(function ($severance) {
                return [
                    'id' => $severance->id,
                    'person' => $severance->person,
                    'severance_type' => $severance->severance_type,
                    'reason' => $severance->reason,
                    'status' => $severance->status,
                    'created_at' => $this->formatDate($severance->created_at),
                    'updated_at' => $this->formatDate($severance->updated_at)
                ];
            }),
            'statuses' => Severance::statuses(),
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
