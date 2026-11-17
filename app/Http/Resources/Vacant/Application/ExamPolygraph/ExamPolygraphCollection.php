<?php

namespace App\Http\Resources\Vacant\Application\ExamPolygraph;

use App\Models\ExamPolygraph;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamPolygraphCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'examPolygraphs' => $this->collection->map(function ($examPolygraph) {
                return [
                    'id' => $examPolygraph->id,
                    'application' => $examPolygraph->application,
                    'date_time' => $this->formatDate($examPolygraph->date_time, 'human'),
                    'city' => $examPolygraph->city,
                    'service_provider' => $examPolygraph->service_provider,
                    'address' => $examPolygraph->address,
                    'neighborhood' => $examPolygraph->neighborhood,
                    'result' => $examPolygraph->result,
                    'description' => $examPolygraph->description,
                    'indication' => $examPolygraph->indication,
                    'status' => $examPolygraph->status,
                    'created_at' => $this->formatDate($examPolygraph->created_at),
                    'updated_at' => $this->formatDate($examPolygraph->updated_at)
                ];
            }),
            'statuses' => ExamPolygraph::statuses(),
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
