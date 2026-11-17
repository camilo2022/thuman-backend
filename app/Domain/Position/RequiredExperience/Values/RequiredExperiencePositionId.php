<?php

declare(strict_types=1);

namespace App\Domain\Position\RequiredExperience\Values;

final class RequiredExperiencePositionId
{
    private $value;

    public function __construct(int $profileId)
    {
        $this->value = $profileId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
