<?php

declare(strict_types=1);

namespace App\Domain\Language\Values;

final class LanguageItemId
{
    private $value;

    public function __construct(int $itemId)
    {
        $this->value = $itemId;
    }

    public function value(): int
    {
        return $this->value;
    }
}
