<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Values;

final class InterviewPsychotechnicalPsychologistStatus
{
    private $value;

    public function __construct(string $psychologistStatus)
    {
        $this->value = $psychologistStatus;
    }

    public function value(): string
    {
        return $this->value;
    }
}
