<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewInitial\Values;

final class InterviewInitialReviewerObservation
{
    private $value;

    public function __construct(string|null $reviewerObservation)
    {
        $this->value = $reviewerObservation;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
