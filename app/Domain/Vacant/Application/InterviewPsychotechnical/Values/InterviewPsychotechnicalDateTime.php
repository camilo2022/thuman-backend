<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Values;

final class InterviewPsychotechnicalDateTime
{
    private $value;

    public function __construct(string $dateTime)
    {
        $this->value = $dateTime;
    }

    public function value(): string
    {
        return $this->value;
    }
}
