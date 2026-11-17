<?php

namespace App\Http\Resources\Contract\Policy;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PolicyCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'policies' => $this->collection->map(function ($policy) {
                return [
                    'id' => $policy->id,
                    'uuid' => $policy->uuid,
                    'model' => $policy->model,
                    'risk_manager' => $policy->risk_manager,
                    'policy_type' => $policy->policy_type,
                    'position' => $policy->position,
                    'code' => $policy->code,
                    'value' => $policy->value,
                    'insured_value' => $policy->insured_value,
                    'start_date' => $policy->start_date,
                    'end_date' => $policy->end_date,
                    'status' => $policy->status,
                    'created_at' => $this->formatDate($policy->created_at),
                    'updated_at' => $this->formatDate($policy->updated_at)
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
