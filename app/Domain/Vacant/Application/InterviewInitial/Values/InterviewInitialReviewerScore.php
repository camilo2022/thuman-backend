<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Values;

final class InterviewInitialReviewerScore
{
    private $value;

    public function __construct(int|null $reviewerScore)
    {
        $this->value = $reviewerScore;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
