<?php

namespace App\Http\Resources\FiscalDocument;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FiscalDocumentCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'fiscalDocuments' => $this->collection->map(function ($fiscalDocument) {
                return [
                    'id' => $fiscalDocument->id,
                    'item' => $fiscalDocument->item,
                    'name' => $fiscalDocument->name,
                    'description' => $fiscalDocument->description,
                    'settings' => $fiscalDocument->settings,
                    'created_at' => $this->formatDate($fiscalDocument->created_at),
                    'updated_at' => $this->formatDate($fiscalDocument->updated_at),
                    'deleted_at' => $this->formatDate($fiscalDocument->deleted_at)
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
