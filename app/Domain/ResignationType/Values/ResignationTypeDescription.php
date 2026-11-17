<?php

declare(strict_types=1);

namespace App\Domain\ResignationType\Values;

final class ResignationTypeDescription
{
    private $value;

    public function __construct(string|null $description)
    {
        $this->value = $description;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
