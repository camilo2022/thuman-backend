<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Headquarter\Values;

final class HeadquarterCreatedAt
{
    private $value;

    public function __construct(string|null $createdAt)
    {
        $this->value = $createdAt;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
