<?php

namespace App\Http\Resources\Form;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FormCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'forms' => $this->collection->map(function ($form) {
                return [
                    'id' => $form->id,
                    'form_type' => $form->form_type,
                    // 'entity' => $form->entity,
                    // 'position' => $form->position,
                    'assignable_type' => $form->assignable_type,
                    'assignable' => $form->assignable,
                    'model_type' => $form->model_type,
                    'model' => $form->model,
                    'questions' => $form->questions,
                    'created_at' => $this->formatDate($form->created_at),
                    'updated_at' => $this->formatDate($form->updated_at),
                    'deleted_at' => $this->formatDate($form->deleted_at)
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
