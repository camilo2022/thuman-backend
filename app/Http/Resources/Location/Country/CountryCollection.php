<?php

namespace App\Http\Resources\Location\Country;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'countries' => $this->collection->map(function ($country) {
                return [
                    'id' => $country->id,
                    'subregion' => $country->subregion,
                    'name' => $country->name,
                    'iso3' => $country->iso3,
                    'iso2' => $country->iso2,
                    'numeric_code' => $country->numeric_code,
                    'phone_code' => $country->phone_code,
                    'currency' => $country->currency,
                    'currency_name' => $country->currency_name,
                    'currency_symbol' => $country->currency_symbol,
                    'tld' => $country->tld,
                    'native' => $country->native,
                    'nationality' => $country->nationality,
                    'latitude' => $country->latitude,
                    'longitude' => $country->longitude,
                    'emoji' => $country->emoji,
                    'emojiU' => $country->emojiU,
                    'translations' => $country->translations,
                    'departaments' => $country->departaments,
                    'created_at' => $this->formatDate($country->created_at),
                    'updated_at' => $this->formatDate($country->updated_at)
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
