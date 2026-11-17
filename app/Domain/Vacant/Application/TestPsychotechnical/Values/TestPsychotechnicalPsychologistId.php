<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestPsychotechnical\Values;

final class TestPsychotechnicalPsychologistId
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
