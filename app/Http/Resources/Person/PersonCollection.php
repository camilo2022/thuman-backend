<?php

namespace App\Http\Resources\Person;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'people' => $this->collection->map(function ($person) {
                return [
                    'id' => $person->id,
                    'first_name' => $person->first_name,
                    'second_name' => $person->second_name,
                    'first_last_name' => $person->first_last_name,
                    'second_last_name' => $person->second_last_name,
                    'birth_date' => $this->formatDate($person->birth_date),
                    'document_number' => $person->document_number,
                    'document_issue_date' => $this->formatDate($person->document_issue_date),
                    'city' => $person->city,
                    'address' => $person->address,
                    'neighborhood' => $person->neighborhood,
                    'dialing_country' => $person->dialing_country,
                    'phone_number' => $person->phone_number,
                    'phone_number_verified_at' => $this->formatDate($person->phone_number_verified_at),
                    'email' => $person->email,
                    'email_verified_at' => $this->formatDate($person->email_verified_at),
                    'document_type' => $person->document_type,
                    'sex' => $person->sex,
                    'gender' => $person->gender,
                    'blood_type' => $person->blood_type,
                    'marital_status' => $person->marital_status,
                    'photo' => $person->photo,
                    'employee' => $person->employee,
                    'created_at' => $this->formatDate($person->created_at),
                    'updated_at' => $this->formatDate($person->updated_at),
                    'deleted_at' => $this->formatDate($person->deleted_at)
                ];
            }),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : $date;
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
