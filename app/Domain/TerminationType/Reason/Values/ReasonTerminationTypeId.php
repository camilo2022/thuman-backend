<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Reason\Values;

final class ReasonTerminationTypeId
{
    private $value;

    public function __construct(int $terminationTypeId)
    {
        $this->value = $terminationTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
