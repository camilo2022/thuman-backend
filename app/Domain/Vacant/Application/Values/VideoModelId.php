<?php

declare(strict_types=1);

namespace App\Domain\Vacant\Application\Values;

final class VideoModelId
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
