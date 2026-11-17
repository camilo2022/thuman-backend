<?php

namespace App\Http\Resources\Profile\AcademicQualification;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AcademicQualificationCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'academicQualifications' => $this->collection->map(function ($academicQualification) {
                return [
                    'id' => $academicQualification->id,
                    'profile' => $academicQualification->profile,
                    'city' => $academicQualification->city,
                    'academic_level' => $academicQualification->academic_level,
                    'academic_institution' => $academicQualification->academic_institution,
                    'academic_title' => $academicQualification->academic_title,
                    'formation_status' => $academicQualification->formation_status,
                    'start_date' => $this->formatDate($academicQualification->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($academicQualification->end_date, 'Y-m-d'),
                    'file' => $academicQualification->file,
                    'created_at' => $this->formatDate($academicQualification->created_at),
                    'updated_at' => $this->formatDate($academicQualification->updated_at)
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
