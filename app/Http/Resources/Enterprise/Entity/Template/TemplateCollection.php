<?php

namespace App\Http\Resources\Enterprise\Entity\Template;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TemplateCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'templates' => $this->collection->map(function ($template) {
                return [
                    'id' => $template->id,
                    'model' => $template->model,
                    'name' => $template->name,
                    'file_type' => $template->file_type,
                    'file_subtype' => $template->file_subtype,
                    'path' => $template->path,
                    'mime' => $template->mime,
                    'extension' => $template->extension,
                    'size' => $template->size,
                    'metadata' => $template->metadata,
                    'created_at' => $this->formatDate($template->created_at),
                    'updated_at' => $this->formatDate($template->updated_at),
                    'deleted_at' => $this->formatDate($template->deleted_at)
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
