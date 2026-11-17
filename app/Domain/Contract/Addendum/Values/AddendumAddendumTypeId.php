<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumAddendumTypeId
{
    private $value;

    public function __construct(int $addendumTypeId)
    {
        $this->value = $addendumTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
