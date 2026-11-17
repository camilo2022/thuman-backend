<?php

namespace App\Http\Resources\Disability;

use App\Models\Disability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DisabilityCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'disabilities' => $this->collection->map(function ($disability) {
                return [
                    'id' => $disability->id,
                    'person' => $disability->person,
                    'disability_type' => $disability->disability_type,
                    'cie_code' => $disability->cie_code ? (object) array_merge($disability->cie_code->toArray(), [
                        'cie_subgroup' => $disability->cie_code?->cie_subgroup?->first() ? (object) array_merge($disability->cie_code->cie_subgroup->first()->toArray(), [
                            'cie_group' => $disability->cie_code->cie_subgroup->first()?->cie_group?->first() ? (object) array_merge($disability->cie_code->cie_subgroup->first()->cie_group->first()->toArray(), [
                                'cie_chapter' => $disability->cie_code->cie_subgroup->first()?->cie_group?->first()?->cie_chapter?->first(),
                            ]) : null,
                        ]) : null,
                    ]) : null,
                    'start_date' => $this->formatDate($disability->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($disability->end_date, 'Y-m-d'),
                    'reason' => $disability->reason,
                    'status' => $disability->status,
                    'created_at' => $this->formatDate($disability->created_at),
                    'updated_at' => $this->formatDate($disability->updated_at)
                ];
            }),
            'statuses' => Disability::statuses(),
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
