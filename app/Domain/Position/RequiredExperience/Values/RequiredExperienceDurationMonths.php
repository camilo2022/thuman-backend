<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredExperience\Values;

final class RequiredExperienceDurationMonths
{
    private $value;

    public function __construct(int $durationMonths)
    {
        $this->value = $durationMonths;
    }

    public function value(): int
    {
        return $this->value;
    }
}
