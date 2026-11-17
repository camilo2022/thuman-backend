<?php

declare(strict_types=1);

namespace App\Domain\RiskManager\Values;

use Illuminate\Support\Collection;

final class RiskManagerRelationships
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
