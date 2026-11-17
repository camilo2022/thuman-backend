<?php

namespace App\Http\Resources\Vacant\Application\InterviewInitial;

use App\Models\InterviewInitial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InterviewInitialCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'interviewInitials' => $this->collection->map(function ($interviewInitial) {
                return [
                    'id' => $interviewInitial->id,
                    'application' => $interviewInitial->application,
                    'date_time' => $this->formatDate($interviewInitial->date_time, 'human'),
                    'form' => $interviewInitial->form,
                    'questions' => $interviewInitial->questions,
                    'answers' => $interviewInitial->answers,
                    'salary_min' => $interviewInitial->salary_min,
                    'salary_max' => $interviewInitial->salary_max,
                    'reviewer_observation' => $interviewInitial->reviewer_observation,
                    'reviewer_score' => $interviewInitial->reviewer_score,
                    'reviewer' => $interviewInitial->reviewer,
                    'reviewer_status' => $interviewInitial->reviewer_status,
                    'leader_observation' => $interviewInitial->leader_observation,
                    'leader_score' => $interviewInitial->leader_score,
                    'leader' => $interviewInitial->leader,
                    'leader_status' => $interviewInitial->leader_status,
                    'indication' => $interviewInitial->indication,
                    'status' => $interviewInitial->status,
                    'created_at' => $this->formatDate($interviewInitial->created_at),
                    'updated_at' => $this->formatDate($interviewInitial->updated_at)
                ];
            }),
            'statuses' => InterviewInitial::statuses(),
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
