<?php

namespace App\Http\Resources\Resignation;

use App\Models\Resignation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResignationCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'resignations' => $this->collection->map(function ($resignation) {
                return [
                    'id' => $resignation->id,
                    'person' => $resignation->person,
                    'resignation_type' => $resignation->resignation_type,
                    'date' => $this->formatDate($resignation->date, 'Y-m-d'),
                    'reason' => $resignation->reason,
                    'status' => $resignation->status,
                    'created_at' => $this->formatDate($resignation->created_at),
                    'updated_at' => $this->formatDate($resignation->updated_at)
                ];
            }),
            'statuses' => Resignation::statuses(),
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
