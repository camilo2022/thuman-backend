<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class ApplicationVacantId
{
    private $value;

    public function __construct(int $vacantId)
    {
        $this->value = $vacantId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
