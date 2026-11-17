<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumContractTypeId
{
    private $value;

    public function __construct(int|null $contractTypeId)
    {
        $this->value = $contractTypeId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
