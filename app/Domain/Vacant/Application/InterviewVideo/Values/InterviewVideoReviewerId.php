<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewVideo\Values;

final class InterviewVideoReviewerId
{
    private $value;

    public function __construct(int|null $reviewerId)
    {
        $this->value = $reviewerId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
