<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Values;

final class InterviewInitialReviewerStatus
{
    private $value;

    public function __construct(string $reviewerStatus)
    {
        $this->value = $reviewerStatus;
    }

    public function value(): string
    {
        return $this->value;
    }
}
