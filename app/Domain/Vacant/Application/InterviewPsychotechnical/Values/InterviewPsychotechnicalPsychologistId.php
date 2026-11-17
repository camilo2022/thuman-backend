<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Values;

final class InterviewPsychotechnicalPsychologistId
{
    private $value;

    public function __construct(int|null $psychologistId)
    {
        $this->value = $psychologistId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
