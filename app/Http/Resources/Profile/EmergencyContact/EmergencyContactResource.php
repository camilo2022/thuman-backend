<?php

namespace App\Http\Resources\Profile\EmergencyContact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'emergencyContact' =>  [
                'id' => $this->id()->value(),
                'profile_id' => $this->profileId()->value(),
                'first_name' => $this->firstName()->value(),
                'second_name' => $this->secondName()->value(),
                'first_last_name' => $this->firstLastName()->value(),
                'second_last_name' => $this->secondLastName()->value(),
                'relationship_id' => $this->relationshipId()->value(),
                'dialing_country_id' => $this->dialingCountryId()->value(),
                'phone_number' => $this->phoneNumber()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }
}
