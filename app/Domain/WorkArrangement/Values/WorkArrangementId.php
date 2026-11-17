<?php

declare(strict_types=1);

namespace App\Domain\WorkArrangement\Values;

final class WorkArrangementId
{
    private $value;

    public function __construct(int|null $id)
    {
        $this->value = $id;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
