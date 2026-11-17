<?php

namespace App\Http\Resources\Location\Country;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'country' => [
                'id' => $this->id()->value(),
                'subregion_id' => $this->subregionId()->value(),
                'name' => $this->name()->value(),
                'iso3' => $this->iso3()->value(),
                'iso2' => $this->iso2()->value(),
                'numeric_code' => $this->numericCode()->value(),
                'phone_code' => $this->phoneCode()->value(),
                'currency' => $this->currency()->value(),
                'currency_name' => $this->currencyName()->value(),
                'currency_symbol' => $this->currencySymbol()->value(),
                'tld' => $this->tld()->value(),
                'native' => $this->native()->value(),
                'nationality' => $this->nationality()->value(),
                'latitude' => $this->latitude()->value(),
                'longitude' => $this->longitude()->value(),
                'emoji' => $this->emoji()->value(),
                'emojiU' => $this->emojiU()->value(),
                'translations' => $this->translations()->value(),
                'settings' => $this->settings()->value(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
            ],
        ];
    }
}
