<?php

namespace App\Http\Resources\PolicyType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PolicyTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'policyTypes' => $this->collection->map(function ($policyType) {
                return [
                    'id' => $policyType->id,
                    'item' => $policyType->item,
                    'name' => $policyType->name,
                    'description' => $policyType->description,
                    'settings' => $policyType->settings,
                    'created_at' => $this->formatDate($policyType->created_at),
                    'updated_at' => $this->formatDate($policyType->updated_at),
                    'deleted_at' => $this->formatDate($policyType->deleted_at)
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
