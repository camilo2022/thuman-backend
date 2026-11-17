<?php

declare(strict_types=1);

namespace App\Domain\ResignationType\Values;

final class ResignationTypeName
{
    private $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
