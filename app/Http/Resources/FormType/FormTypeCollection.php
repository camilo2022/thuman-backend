<?php

namespace App\Http\Resources\FormType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FormTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'formTypes' => $this->collection->map(function ($formType) {
                return [
                    'id' => $formType->id,
                    'item' => $formType->item,
                    'name' => $formType->name,
                    'description' => $formType->description,
                    'settings' => $formType->settings,
                    'created_at' => $this->formatDate($formType->created_at),
                    'updated_at' => $this->formatDate($formType->updated_at),
                    'deleted_at' => $this->formatDate($formType->deleted_at)
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
