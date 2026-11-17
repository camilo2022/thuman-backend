<?php

declare(strict_types=1);

namespace App\Domain\TerminationType\Reason\Values;

final class ReasonDeletedAt
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
