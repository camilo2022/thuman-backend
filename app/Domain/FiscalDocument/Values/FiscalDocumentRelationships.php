<?php

declare(strict_types=1);

namespace App\Domain\FiscalDocument\Values;

use Illuminate\Support\Collection;

final class FiscalDocumentRelationships
{
    private Collection $countries;

    public function __construct(
        ?Collection $countries = null
    ) {
        $this->countries = $countries ?? collect();
    }

    public function countries(): Collection
    {
        return $this->countries;
    }

    public function toArray(): array
    {
        return [
            'countries' => $this->countries
        ];
    }
}
