<?php

namespace App\Http\Resources\Vacant\Application\InterviewPsychotechnical;

use App\Models\InterviewPsychotechnical;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InterviewPsychotechnicalCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'interviewPsychotechnicals' => $this->collection->map(function ($interviewPsychotechnical) {
                return [
                    'id' => $interviewPsychotechnical->id,
                    'application' => $interviewPsychotechnical->application,
                    'date_time' => $this->formatDate($interviewPsychotechnical->date_time, 'human'),
                    'questions' => $interviewPsychotechnical->questions,
                    'answers' => $interviewPsychotechnical->answers,
                    'psychologist_observation' => $interviewPsychotechnical->psychologist_observation,
                    'psychologist_score' => $interviewPsychotechnical->psychologist_score,
                    'psychologist' => $interviewPsychotechnical->psychologist,
                    'psychologist_status' => $interviewPsychotechnical->psychologist_status,
                    'indication' => $interviewPsychotechnical->indication,
                    'status' => $interviewPsychotechnical->status,
                    'created_at' => $this->formatDate($interviewPsychotechnical->created_at),
                    'updated_at' => $this->formatDate($interviewPsychotechnical->updated_at)
                ];
            }),
            'statuses' => InterviewPsychotechnical::statuses(),
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
