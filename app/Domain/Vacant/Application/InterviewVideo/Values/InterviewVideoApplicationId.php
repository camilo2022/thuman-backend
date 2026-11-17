<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewVideo\Values;

final class InterviewVideoApplicationId
{
    private $value;

    public function __construct(int $applicationId)
    {
        $this->value = $applicationId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
