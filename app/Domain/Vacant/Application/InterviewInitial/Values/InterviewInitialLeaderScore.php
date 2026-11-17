<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Values;

final class InterviewInitialLeaderScore
{
    private $value;

    public function __construct(int|null $leaderScore)
    {
        $this->value = $leaderScore;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
