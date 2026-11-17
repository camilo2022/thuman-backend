<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumContractId
{
    private $value;

    public function __construct(int $contractId)
    {
        $this->value = $contractId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
