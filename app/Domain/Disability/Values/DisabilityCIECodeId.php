<?php

declare(strict_types=1);

namespace App\Domain\Disability\Values;

final class DisabilityCIECodeId
{
    private $value;

    public function __construct(int $cieCodeId)
    {
        $this->value = $cieCodeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
