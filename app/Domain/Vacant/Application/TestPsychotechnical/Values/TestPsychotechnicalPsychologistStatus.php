<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Values;

final class TestPsychotechnicalPsychologistStatus
{
    private $value;

    public function __construct(string|null $psychologistStatus)
    {
        $this->value = $psychologistStatus;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
