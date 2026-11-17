<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Values;

final class InterviewPsychotechnicalRelationships
{
    private ?object $application;
    private ?object $psychologist;

    public function __construct(
        ?object $application = null,
        ?object $psychologist = null
    ) {
        $this->application = $application;
        $this->psychologist = $psychologist;
    }

    public function application(): ?object
    {
        return $this->application;
    }

    public function psychologist(): ?object
    {
        return $this->psychologist;
    }

    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'psychologist' => $this->psychologist
        ];
    }
}
