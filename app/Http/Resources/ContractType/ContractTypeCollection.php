<?php

namespace App\Http\Resources\ContractType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'contractTypes' => $this->collection->map(function ($contractType) {
                return [
                    'id' => $contractType->id,
                    'item' => $contractType->item,
                    'name' => $contractType->name,
                    'description' => $contractType->description,
                    'settings' => $contractType->settings,
                    'file_subtypes' => $contractType->file_subtypes,
                    'created_at' => $this->formatDate($contractType->created_at),
                    'updated_at' => $this->formatDate($contractType->updated_at),
                    'deleted_at' => $this->formatDate($contractType->deleted_at)
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
