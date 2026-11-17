<?php

declare(strict_types=1);

namespace App\Domain\CompensationFundAffiliation\Values;

final class FileUpdatedAt
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
