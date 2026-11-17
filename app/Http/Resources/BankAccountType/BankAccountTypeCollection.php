<?php

namespace App\Http\Resources\BankAccountType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class BankAccountTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'bankAccountTypes' => $this->collection->map(function ($bankAccountType) {
                return [
                    'id' => $bankAccountType->id,
                    'item' => $bankAccountType->item,
                    'name' => $bankAccountType->name,
                    'description' => $bankAccountType->description,
                    'settings' => $bankAccountType->settings,
                    'created_at' => $this->formatDate($bankAccountType->created_at),
                    'updated_at' => $this->formatDate($bankAccountType->updated_at),
                    'deleted_at' => $this->formatDate($bankAccountType->deleted_at)
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
