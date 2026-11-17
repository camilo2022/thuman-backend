<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumPersonStatus
{
    private $value;

    public function __construct(string|null $personStatus)
    {
        $this->value = $personStatus;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
