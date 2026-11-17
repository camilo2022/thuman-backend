<?php

declare(strict_types=1);

namespace App\Domain\Contract\Addendum\Values;

final class AddendumDeletedAt
{
    private $value;

    public function __construct(string|null $deletedAt)
    {
        $this->value = $deletedAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
