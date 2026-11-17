<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Values;

final class InterviewPsychotechnicalPsychologistObservation
{
    private $value;

    public function __construct(string|null $psychologistObservation)
    {
        $this->value = $psychologistObservation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
