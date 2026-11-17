<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Values;

final class InterviewPsychotechnicalPsychologistScore
{
    private $value;

    public function __construct(int|null $psychologistScore)
    {
        $this->value = $psychologistScore;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
