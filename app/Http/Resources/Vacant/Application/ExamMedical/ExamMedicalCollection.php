<?php

namespace App\Http\Resources\Vacant\Application\ExamMedical;

use App\Models\ExamMedical;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamMedicalCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'examMedicals' => $this->collection->map(function ($examMedical) {
                return [
                    'id' => $examMedical->id,
                    'application' => $examMedical->application,
                    'date_time' => $this->formatDate($examMedical->date_time, 'human'),
                    'city' => $examMedical->city,
                    'health_entity' => $examMedical->health_entity,
                    'address' => $examMedical->address,
                    'neighborhood' => $examMedical->neighborhood,
                    'result' => $examMedical->result,
                    'epidemiological_program' => $examMedical->epidemiological_program,
                    'description' => $examMedical->description,
                    'indication' => $examMedical->indication,
                    'status' => $examMedical->status,
                    'created_at' => $this->formatDate($examMedical->created_at),
                    'updated_at' => $this->formatDate($examMedical->updated_at)
                ];
            }),
            'statuses' => ExamMedical::statuses(),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        if ($format === 'human') {
            Carbon::setLocale('es');
            return Carbon::parse($date)->translatedFormat('l j \d\e F \d\e Y \a \l\a\s g:i a');
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
