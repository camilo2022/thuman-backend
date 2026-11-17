<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantUuid
{
    private $value;

    public function __construct(string|null $uuid)
    {
        $this->value = $uuid;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
