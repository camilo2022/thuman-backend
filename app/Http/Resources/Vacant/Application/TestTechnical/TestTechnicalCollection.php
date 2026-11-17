<?php

namespace App\Http\Resources\Vacant\Application\TestTechnical;

use App\Models\TestTechnical;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestTechnicalCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'testTechnicals' => $this->collection->map(function ($testTechnical) {
                return [
                    'id' => $testTechnical->id,
                    'application' => $testTechnical->application,
                    'start_date' => $this->formatDate($testTechnical->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($testTechnical->end_date, 'Y-m-d'),
                    'form' => $testTechnical->form,
                    'questions' => $testTechnical->questions,
                    'answers' => $testTechnical->answers,
                    'leader_observation' => $testTechnical->leader_observation,
                    //'leader_score' => $testTechnical->leader_score,
                    'leader' => $testTechnical->leader,
                    'leader_status' => $testTechnical->leader_status,
                    'start_at' => $this->formatDate($testTechnical->start_at),
                    'end_at' => $this->formatDate($testTechnical->end_at),
                    'indication' => $testTechnical->indication,
                    'status' => $testTechnical->status,
                    'created_at' => $this->formatDate($testTechnical->created_at),
                    'updated_at' => $this->formatDate($testTechnical->updated_at)
                ];
            }),
            'statuses' => TestTechnical::statuses(),
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
