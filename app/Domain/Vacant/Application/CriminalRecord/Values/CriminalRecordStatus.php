<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\CriminalRecord\Values;

final class CriminalRecordStatus
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
