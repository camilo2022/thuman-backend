<?php

declare(strict_types=1);

namespace App\Domain\Resignation\Values;

final class ResignationResignationTypeId
{
    private $value;

    public function __construct(int $resignationTypeId)
    {
        $this->value = $resignationTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
