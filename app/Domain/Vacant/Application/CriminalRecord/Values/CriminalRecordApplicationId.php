<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\CriminalRecord\Values;

final class CriminalRecordApplicationId
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
