<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\ExamPolygraph\Values;

final class ExamPolygraphRelationships
{
    private ?object $application;
    private ?object $city;
    private ?object $service_provider;
    private ?object $file;

    public function __construct(
        ?object $application = null,
        ?object $city = null,
        ?object $service_provider = null,
        ?object $file = null
    ) {
        $this->application = $application;
        $this->city = $city;
        $this->service_provider = $service_provider;
        $this->file = $file;
    }

    public function application(): ?object
    {
        return $this->application;
    }

    public function city(): ?object
    {
        return $this->city;
    }

    public function serviceProvider(): ?object
    {
        return $this->service_provider;
    }

    public function file(): ?object
    {
        return $this->file;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'city' => $this->city,
            'service_provider' => $this->service_provider,
            'file' => $this->file()
        ];
    }
}
