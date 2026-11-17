<?php

declare(strict_types=1);

namespace App\Domain\Termination\Values;

final class TerminationModelId
{
    private $value;

    public function __construct(int|null $modelId)
    {
        $this->value = $modelId;
    }

    public function value(): int|null
    {
        return $this->value;
    }
}
