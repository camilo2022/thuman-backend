<?php

namespace App\Http\Resources\Survey;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SurveyCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'surveys' => $this->collection->map(function ($form) {
                return [
                    'id' => $form->id,
                    'form' => $form->form,
                    'questions' => $form->questions,
                    'answers' => $form->answers,
                    'person' => $form->person,
                    'created_at' => $this->formatDate($form->created_at, 'd F Y'),
                    'updated_at' => $this->formatDate($form->updated_at)
                ];
            }),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        if ($format === 'd F Y') {
            Carbon::setLocale('es');
            return Carbon::parse($date)->translatedFormat('d F Y');
        }
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
