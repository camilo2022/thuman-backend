<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantWorkArrangementId
{
    private $value;

    public function __construct(int $workArrangementId)
    {
        $this->value = $workArrangementId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
