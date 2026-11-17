<?php

namespace App\Http\Resources\Employee;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'employees' => $this->collection->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'person' => $employee->person,
                    'headquarter' => $employee->headquarter,
                    'position' => $employee->position,
                    'campaign' => $employee->campaign,
                    'start_date' => $this->formatDate($employee->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($employee->end_date, 'Y-m-d'),
                    'created_at' => $this->formatDate($employee->created_at),
                    'updated_at' => $this->formatDate($employee->updated_at),
                    'deleted_at' => $this->formatDate($employee->deleted_at)
                ];
            }),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
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
