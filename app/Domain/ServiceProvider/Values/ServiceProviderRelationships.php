<?php

declare(strict_types=1);

namespace App\Domain\ServiceProvider\Values;

use Illuminate\Support\Collection;

final class ServiceProviderRelationships
{
    private Collection $service_types;
    private Collection $countries;

    public function __construct(
        ?Collection $service_types = null,
        ?Collection $countries = null
    ) {
        $this->service_types = $service_types ?? collect();
        $this->countries = $countries ?? collect();
    }

    public function serviceTypes(): Collection
    {
        return $this->service_types;
    }

    public function countries(): Collection
    {
        return $this->countries;
    }

    public function toArray(): array
    {
        return [
            'service_types' => $this->service_types,
            'countries' => $this->countries
        ];
    }
}
