<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumHeadquarterId
{
    private $value;

    public function __construct(int|null $headquarterId)
    {
        $this->value = $headquarterId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
