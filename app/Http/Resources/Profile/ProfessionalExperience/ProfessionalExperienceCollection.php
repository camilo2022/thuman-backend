<?php

namespace App\Http\Resources\Profile\ProfessionalExperience;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfessionalExperienceCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'professionalExperiences' => $this->collection->map(function ($professionalExperience) {
                return [
                    'id' => $professionalExperience->id,
                    'profile' => $professionalExperience->profile,
                    'company_name' => $professionalExperience->company_name,
                    'supervisor_name' => $professionalExperience->supervisor_name,
                    'supervisor_dialing_country' => $professionalExperience->supervisor_dialing_country,
                    'supervisor_phone_number' => $professionalExperience->supervisor_phone_number,
                    'city' => $professionalExperience->city,
                    'company_address' => $professionalExperience->company_address,
                    'start_date' => $this->formatDate($professionalExperience->start_date,'Y-m-d'),
                    'end_date' => $this->formatDate($professionalExperience->end_date,'Y-m-d'),
                    'position' => $professionalExperience->position,
                    'work_arrangement' => $professionalExperience->work_arrangement,
                    'position_description' => $professionalExperience->position_description,
                    'file' => $professionalExperience->file,
                    'created_at' => $this->formatDate($professionalExperience->created_at),
                    'updated_at' => $this->formatDate($professionalExperience->updated_at)
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

