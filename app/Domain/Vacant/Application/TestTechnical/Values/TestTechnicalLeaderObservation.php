<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalLeaderObservation
{
    private $value;

    public function __construct(string|null $leaderObservation)
    {
        $this->value = $leaderObservation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
