<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\TestTechnical\Values;

final class TestTechnicalLeaderStatus
{
    private $value;

    public function __construct(string|null $leaderStatus)
    {
        $this->value = $leaderStatus;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
