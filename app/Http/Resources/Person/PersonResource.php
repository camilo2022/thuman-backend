<?php

namespace App\Http\Resources\Person;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'person' => [
                'id' => $this->id()->value(),
                'first_name' => $this->firstName()->value(),
                'second_name' => $this->secondName()->value(),
                'first_last_name' => $this->firstLastName()->value(),
                'second_last_name' => $this->secondLastName()->value(),
                'birth_date' => $this->birthDate()->value(),
                'birth_city_id' => $this->birthCityId()->value(),
                'document_type_id' => $this->documentTypeId()->value(),
                'document_issue_city_id' => $this->documentIssueCityId()->value(),
                'document_number' => $this->documentNumber()->value(),
                'document_issue_date' => $this->documentIssueDate()->value(),
                'city_id' => $this->cityId()->value(),
                'address' => $this->address()->value(),
                'neighborhood' => $this->neighborhood()->value(),
                'dialing_country_id' => $this->dialingCountryId()->value(),
                'phone_number' => $this->phoneNumber()->value(),
                'phone_number_verified_at' => $this->phoneNumberVerifiedAt()->value(),
                'email' => $this->email()->value(),
                'email_verified_at' => $this->emailVerifiedAt()->value(),
                'sex_id' => $this->sexId()->value(),
                'gender_id' => $this->genderId()->value(),
                'blood_type_id' => $this->bloodTypeId()->value(),
                'marital_status_id' => $this->maritalStatusId()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
