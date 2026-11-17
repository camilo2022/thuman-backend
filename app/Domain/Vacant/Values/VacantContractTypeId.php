<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Values;

final class VacantContractTypeId
{
    private $value;

    public function __construct(int $contractTypeId)
    {
        $this->value = $contractTypeId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
