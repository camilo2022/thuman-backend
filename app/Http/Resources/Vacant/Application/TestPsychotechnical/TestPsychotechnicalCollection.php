<?php

namespace App\Http\Resources\Vacant\Application\TestPsychotechnical;

use App\Models\TestPsychotechnical;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestPsychotechnicalCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'testPsychotechnicals' => $this->collection->map(function ($testPsychotechnical) {
                return [
                    'id' => $testPsychotechnical->id,
                    'application' => $testPsychotechnical->application,
                    'start_date' => $this->formatDate($testPsychotechnical->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($testPsychotechnical->end_date, 'Y-m-d'),
                    'form' => $testPsychotechnical->form,
                    'questions' => $testPsychotechnical->questions,
                    'answers' => $testPsychotechnical->answers,
                    'psychologist_observation' => $testPsychotechnical->psychologist_observation,
                    //'psychologist_score' => $testPsychotechnical->psychologist_score,
                    'psychologist' => $testPsychotechnical->psychologist,
                    'psychologist_status' => $testPsychotechnical->psychologist_status,
                    'start_at' => $this->formatDate($testPsychotechnical->start_at),
                    'end_at' => $this->formatDate($testPsychotechnical->end_at),
                    'indication' => $testPsychotechnical->indication,
                    'status' => $testPsychotechnical->status,
                    'created_at' => $this->formatDate($testPsychotechnical->created_at),
                    'updated_at' => $this->formatDate($testPsychotechnical->updated_at)
                ];
            }),
            'statuses' => TestPsychotechnical::statuses(),
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
