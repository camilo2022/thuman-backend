<?php

namespace App\Http\Resources\Profile\LanguageProficiencie;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LanguageProficiencieCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'languageProficiencies' => $this->collection->map(function ($languageProficiencie) {
                return [
                    'id' => $languageProficiencie->id,
                    'profile' => $languageProficiencie->profile,
                    'language' => $languageProficiencie->language,
                    'language_level' => $languageProficiencie->language_level,
                    'file' => $languageProficiencie->file,
                    'created_at' => $this->formatDate($languageProficiencie->created_at),
                    'updated_at' => $this->formatDate($languageProficiencie->updated_at)
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

