<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Values;

final class IconModelId
{
    private $value;

    public function __construct(int $modelId)
    {
        $this->value = $modelId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
