<?php

namespace App\Http\Resources\Profile\LanguageProficiencie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageProficiencieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'languageProficiencie' => [
                'id' => $this->id()->value(),
                'profile' => $this->profileId()->value(),
                'language_id' => $this->languageId()->value(),
                'language_level_id' => $this->languageLevelId()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value()
            ],
        ];
    }
}
