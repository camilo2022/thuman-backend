<?php

declare(strict_types=1);

namespace App\Domain\ResignationType\Values;

final class ResignationTypeUpdatedAt
{
    private $value;

    public function __construct(string|null $updatedAt)
    {
        $this->value = $updatedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
