<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\InterviewPsychotechnical\Values;

final class InterviewPsychotechnicalStatus
{
    private $value;

    public function __construct(string $status)
    {
        $this->value = $status;
    }

    public function value(): string
    {
        return $this->value;
    }
}
